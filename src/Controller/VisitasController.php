<?php
/**
 * Controlador de visitas
 */
namespace App\Controller;

// SYMFONY FRAMEWORK
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// COMPONENTS
use Matrix\Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

//ENTITIES
use App\Core\Entity\Contact;
use App\Core\Entity\Vocabs\ContactKind;
use App\Entity\Charge;
use App\Entity\CommercialOffice;
use App\Entity\MasterOffice;
use App\Entity\Reason;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\Vertical;
use App\Entity\Views\Event;
use App\Entity\Visit;
use App\Form\Type\EventSearchType;

/**
 * Class VisitasController
 */
class VisitasController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * VisitasController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Obtains a list of calendar events filtered by the search filters
     * provided on the request object.
     *
     * @param Request $request Request object
     *
     * @return JsonResponse
     */
    public function getCalendarEvents(Request $request): JsonResponse
    {
        // Fullcalendar.js encodes arrays as comma-separated values,
        // so we need to transform the values into arrays

        $values                    = $request->query->all();
        isset($values['author_id'][0])
            ? $values['author_id'] = $this->asArray(0, $values['author_id'])
            : $values['author_id'] = null;
        $values['status_id']       = $this->asArray('status_id', $values);

        // This validates the search form and obtains its values, then
        // fetches the calendar events filtered by the form data

        $form = $this->createForm(EventSearchType::class);
        $form->submit($values, false);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $errors = ['errors' => $this->getFormErrors($form)];

            return $this->json($errors, 400);
        }

        $registry   = $this->getDoctrine();
        $repository = $registry->getRepository(Event::class);
        $results    = $repository->search($form->getData());

        return $this->json($results, 200);
    }


    /**
     * Actualiza un evento de calendario mediante el ID enviado por request
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY", statusCode=401)
     */
    public function updateCalendarEvent(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $visitUpdated  = $this->getDoctrine()->getRepository(Visit::class)->findOneBy(['id' => $request->request->get('idEvento')]);
        $dateStart     = new \DateTime($request->request->get('newDateStart'));
        $dateEnd       = new \DateTime($request->request->get('newDateEnd'));
        $interval      = $dateStart->diff($dateEnd);
        $array         = [$interval->h, $interval->i];
        $duration      = implode(':', $array);

        $visitUpdated->setDateIni($dateStart);
        $visitUpdated->setDateFin($dateEnd);
        $visitUpdated->setDuration($duration);

        try {
            $entityManager->persist($visitUpdated);
            $entityManager->flush();

            return new Response();
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Recibe el ID de una visita mediante Request y devuelve la información de una visita formateada para
     * el modal de frontend
     * @param Request             $request
     * @param TranslatorInterface $translator
     *
     * @return JsonResponse|Response
     */
    public function getFullVisit(Request $request, TranslatorInterface $translator)
    {

        try {
            $visit    = $this->getDoctrine()->getRepository(Visit::class)->findOneBy(['id' => $request->request->get('idVisita')]);
            $vertical = $this->getDoctrine()->getRepository(Vertical::class)->findOneBy(['id' => $visit->getVertical()]);
            $reason   = $this->getDoctrine()->getRepository(Reason::class)->findOneBy(['id' => $visit->getReason()]);
            $status   = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => $visit->getStatus()]);

            /*
             * Construccion del modal de información de oficina
             */

            /* INFORMACIÓN GENERAL */
            $outputInfo  = '<div class="col-lg-12 col-md-12">';
            $outputInfo .= '<h6>'.$translator->trans('Visit_info').'</h6><hr>';

            if ($visit->getCustomerID()) {
                $person = $this->getDoctrine()->getRepository(Contact::class)->findOneBy(['nif' => $visit->getCustomerID()]);
                if ($person) {
                    $personText  = $this->getNamePersonFormatted($person->getNif(), $person->getName(), $person->getKind()->getName());
                    $outputInfo .= '<p><strong>'.$translator->trans('customer').':</strong> '.$personText.'</p>';

                    if ($visit->getCustomerCharge()) {
                        $customerCharge = $this->getDoctrine()->getRepository(Charge::class)->findOneBy(['id' => $visit->getCustomerCharge()]);
                        $outputInfo    .= '<p><strong>'.$translator->trans('charge').':</strong> '.$customerCharge->getName().'</p>';
                    }

                    if ($visit->getCustomerChargeAnother()) {
                        $outputInfo .= '<p><strong>'.$translator->trans('another').':</strong> '.$visit->getCustomerChargeAnother().'</p>';
                    }
                }
            }

            if ($visit->getProviderID()) {
                $person = $this->getDoctrine()->getRepository(Contact::class)->findOneBy(['nif' => $visit->getProviderID()]);
                if ($person) {
                    $personText  = $this->getNamePersonFormatted($person->getNif(), $person->getName(), $person->getKind()->getName());
                    $outputInfo .= '<p><strong>'.$translator->trans('provider').':</strong> '.$personText.'</p>';

                    if ($visit->getProviderCharge()) {
                        $providerCharge = $this->getDoctrine()->getRepository(Charge::class)->findOneBy(['id' => $visit->getProviderCharge()]);
                        $outputInfo    .= '<p><strong>'.$translator->trans('charge').':</strong> '.$providerCharge->getName().'</p>';
                    }

                    if ($visit->getProviderChargeAnother()) {
                        $outputInfo .= '<p><strong>'.$translator->trans('another').':</strong> '.$visit->getProviderChargeAnother().'</p>';
                    }
                }
            }

            if ($visit->getOffice()) {
                $office = $this->getDoctrine()->getRepository(MasterOffice::class)->findOneBy(['codigo' => $visit->getOffice()]);
                if ($office) {
                    $officetext       = $office->getNombre();
                    $outputInfo      .= '<p><strong>'.$translator->trans('Office').':</strong> '.$officetext.'</p>';
                    $commercialOffice = $this->getDoctrine()->getRepository(CommercialOffice::class)->findOneBy(['Office' => $visit->getOffice()]);
                    if ($commercialOffice) {
                        $commercial = $commercialOffice->getCommercial();

                        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['regNumber' => $commercial]);
                        if ($user) {
                            $nameUser       = $user->getName();
                            $surnameUser    = $user->getSurname();
                            $nameCommercial = '('.$commercial.') '.$nameUser.' '.$surnameUser;
                            $outputInfo    .= '<p><strong>'.$translator->trans('commercial assigned to the office').':</strong> '.$nameCommercial.'</p>';
                        }
                    }
                }
            }

            $outputInfo .= '<p><strong>'.$translator->trans('initial date').':</strong> '.$visit->getDateIni()->format('d/m/Y H:i').'</p>';
            $outputInfo .= '<p><strong>'.$translator->trans('Duration').':</strong> '.$visit->getDuration().'</p>';
            $outputInfo .= '<p><strong>'.$translator->trans('vertical').':</strong> '.$vertical->getName().'</p>';
            $outputInfo .= '<p><strong>'.$translator->trans('reason').':</strong> '.$reason->getName().'</p>';
            if ($visit->getType() === 1) {
                $outputInfo .= '<p><strong>'.$translator->trans('contact type').':</strong> '.$translator->trans('presencial').'</p>';
            } else {
                $outputInfo .= '<p><strong>'.$translator->trans('contact type').':</strong> '.$translator->trans('virtual').'</p>';
            }
            if ($visit->getObservations()) {
                $outputInfo .= '<p><strong>'.$translator->trans('observations').':</strong> '.$visit->getObservations().'</p>';
            }
            if ($visit->getFeedback()) {
                $outputInfo .= '<p><strong>'.$translator->trans('feedback').':</strong>'.$visit->getFeedback().'</p>';
            }
            $outputInfo .= '<p><strong>'.$translator->trans('status').':</strong> '.$status->getName().'</p>';
            $outputInfo .= '</div>';

            $output = '<div class="col-lg-12" style="font-size: 12px"><div class="row">'.$outputInfo.'</div>';

            return new JsonResponse($output, 200);
        } catch (Exception $e) {
            return new Response('There was an error running the query, try again later...', $e->getCode());
        }
    }

    /**
     * insert and edit form visit
     * @return Response
     */
    public function visitForm(): Response
    {

        $visits          = [];
        $statusCancel    = '';
        $customerid      = '';
        $customertext    = '';
        $providerid      = '';
        $providertext    = '';
        $typeForm        = 'A';
        $officeLoad      = '';
        $nameResponsable = '';

        if (isset($_GET['idVisita'])) {
            $id       = $_GET['idVisita'];
            $typeForm = 'E';
            $visits   = $this->getDoctrine()->getRepository(Visit::class)->findOneBy(['id' => $id]);

            if ($visits) {
                //get status
                $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => $visits->getStatus()]);
                if ($status) {
                    $statusID     = $status->getId();
                    $statusCancel = Visit::CANCELED === $statusID ? true : false;
                }

                $office = $this->getDoctrine()->getRepository(MasterOffice::class)->findOneBy(['codigo' => $visits->getOffice()]);
                if ($office) {
                    $officeLoad = $office->getNombre();
                }

                if ($visits->getCustomerID()) {
                    $person = $this->getDoctrine()->getRepository(Contact::class)->findOneBy(['nif' => $visits->getCustomerID()]);
                    if ($person) {
                        $customerid   = $person->getNif();
                        $customertext = $this->getNamePersonFormatted($person->getNif(), $person->getName(), $person->getKind()->getName());
                    }
                }

                if ($visits->getProviderID()) {
                    $person = $this->getDoctrine()->getRepository(Contact::class)->findOneBy(['nif' => $visits->getProviderID()]);
                    if ($person) {
                        $providerid   = $person->getNif();
                        $providertext = $this->getNamePersonFormatted($person->getNif(), $person->getName(), $person->getKind()->getName());
                    }
                }
            }
        }

        $allStatus     = $this->getDoctrine()->getRepository(Status::class)->findAll();
        $allCharges    = $this->getDoctrine()->getRepository(Charge::class)->findAll();
        $tiposContacto = ContactKind::entities();

        $allVerticals = $this->getDoctrine()->getRepository(Vertical::class)->findAll();
        $allReasons   = $this->getDoctrine()->getRepository(Reason::class)->findAll();

        $method = isset($_POST['method']) ? 'post' : 'get';

        return $this->render(
            'area-comercial/visit.html.twig',
            [
                'visits'          => $visits,
                'statusCancel'    => $statusCancel,
                'customerid'      => $customerid,
                'customertext'    => $customertext,
                'providerid'      => $providerid,
                'providertext'    => $providertext,
                'officeLoad'      => $officeLoad,
                'nameResponsable' => $nameResponsable,
                'allCharges'      => $allCharges,
                'allVerticals'    => $allVerticals,
                'allReasons'      => $allReasons,
                'allStatus'       => $allStatus,
                'tipos_contacto'  => $tiposContacto,
                'method'          => $method,
                'typeForm'        => $typeForm,
            ]
        );
    }

    /**
     * Add / Edit Visit
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY", statusCode=401)
     */
    public function saveVisit(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($request->request->get('typeForm') === 'A') {
            /**
             * Add Visita
             */
            $visit = new Visit();

            $type = '1' === $request->request->get('type') ? 1 : 0;

            if ($request->request->get('Customer')) {
                $visit->setCustomerID($request->request->get('Customer'));
            }
            if ($request->request->get('chargeCustomer') > 0) {
                $chargeCustomer = $this->getDoctrine()->getRepository(Charge::class)->findOneBy(['id' => $request->request->get('chargeCustomer')]);
                $visit->setCustomerCharge($chargeCustomer);
            }
            if ($request->request->get('anotherC')) {
                $visit->setCustomerChargeAnother($request->request->get('anotherC'));
            }
            if ($request->request->get('Provider')) {
                $visit->setProviderID($request->request->get('Provider'));
            }
            if ($request->request->get('chargeProvider') > 0) {
                $chargeProvider = $this->getDoctrine()->getRepository(Charge::class)->findOneBy(['id' => $request->request->get('chargeProvider')]);
                $visit->setProviderCharge($chargeProvider);
            }
            if ($request->request->get('anotherP')) {
                $visit->setProviderChargeAnother($request->request->get('anotherP'));
            }


            if ($request->request->get('Office') > 0) {
                $visit->setOffice($request->request->get('Office'));
            }
            if ($request->request->get('vertical') > 0) {
                $vertical = $this->getDoctrine()->getRepository(Vertical::class)->findOneBy(['id' => $request->request->get('vertical')]);
                $visit->setVertical($vertical);
            }
            if ($request->request->get('reason') > 0) {
                $reason = $this->getDoctrine()->getRepository(Reason::class)->findOneBy(['id' => $request->request->get('reason')]);
                $visit->setReason($reason);
            }

            $visit->setType($type);

            if ($request->request->get('obsVisit')) {
                $visit->setObservations($request->request->get('obsVisit'));
            }

            /**
             * Feedback
             */
            if ($request->request->get('feedback')) {
                $visit->setFeedback($request->request->get('feedback'));
                $statusID = Visit::DONE;
            } else {
                $statusID = Visit::PENDING;
            }

            /**
             * status
             */
            $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => $statusID]);
            $visit->setStatus($status);

            /**
             * Fechas
             */
            $dateIni = \DateTime::createFromFormat('d/m/Y H:i', $request->request->get('dateIni'))->format('Y-m-d H:i:s');
            $visit->setDateIni(new \DateTime($dateIni));

            $visit->setDuration($request->request->get('duration'));

            $duration = explode(':', $request->request->get('duration'));
            $dateFin  = \DateTime::createFromFormat('d/m/Y H:i', $request->request->get('dateIni'))->add(new \DateInterval('PT'.$duration[0].'H'.$duration[1].'M'))->format('Y-m-d H:i:s');

            $visit->setDateFin(new \DateTime($dateFin));

            $entityManager->persist($visit);
            $entityManager->flush();
        } else {
            /**
             * Editar Visita
             */
            $visit = $this->getDoctrine()->getRepository(Visit::class)->findOneBy(['id' => $request->request->get('visitid')]);

            $type = '1' === $request->request->get('type') ? 1 : 0;

            if ($request->request->get('Customer')) {
                $visit->setCustomerID($request->request->get('Customer'));
            } else {
                $visit->setCustomerID(null);
            }

            if ($request->request->get('chargeCustomer')) {
                $chargeCustomer = $this->getDoctrine()->getRepository(Charge::class)->findOneBy(['id' => $request->request->get('chargeCustomer')]);
                $visit->setCustomerCharge($chargeCustomer);
            } else {
                $visit->setCustomerCharge(null);
            }
            if ($request->request->get('anotherC')) {
                $visit->setCustomerChargeAnother($request->request->get('anotherC'));
            } else {
                $visit->setCustomerChargeAnother(null);
            }
            if ($request->request->get('Provider')) {
                $visit->setProviderID($request->request->get('Provider'));
            } else {
                $visit->setProviderID(null);
            }
            if ($request->request->get('chargeProvider')) {
                $chargeProvider = $this->getDoctrine()->getRepository(Charge::class)->findOneBy(['id' => $request->request->get('chargeProvider')]);
                $visit->setProviderCharge($chargeProvider);
            } else {
                $visit->setProviderCharge(null);
            }
            if ($request->request->get('anotherP')) {
                $visit->setProviderChargeAnother($request->request->get('anotherP'));
            } else {
                $visit->setProviderChargeAnother(null);
            }
            if ($request->request->get('Office')) {
                $visit->setOffice($request->request->get('Office'));
            } else {
                $visit->setOffice(null);
            }
            $vertical = $this->getDoctrine()->getRepository(Vertical::class)->findOneBy(['id' => $request->request->get('vertical')]);
            $visit->setVertical($vertical);
            $reason = $this->getDoctrine()->getRepository(Reason::class)->findOneBy(['id' => $request->request->get('reason')]);
            $visit->setReason($reason);
            $visit->setType($type);
            $visit->setObservations($request->request->get('obsVisit'));

            /**
             * Feedback
             */
            if ($request->request->get('feedback')) {
                $visit->setFeedback($request->request->get('feedback'));
                $statusID = Visit::DONE;
            } else {
                $visit->setFeedback(null);
                $statusID = Visit::PENDING;
            }

            /**
             * Status
             */
            $status = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => $statusID]);
            $visit->setStatus($status);

            /**
             * Fechas
             */
            $date = \DateTime::createFromFormat('d/m/Y H:i', $request->request->get('dateIni'))->format('Y-m-d H:i:s');
            $visit->setDateIni(new \DateTime($date));

            $visit->setDuration($request->request->get('duration'));

            $duration = explode(':', $request->request->get('duration'));
            $dateFin  = \DateTime::createFromFormat('d/m/Y H:i', $request->request->get('dateIni'))->add(new \DateInterval('PT'.$duration[0].'H'.$duration[1].'M'))->format('Y-m-d H:i:s');

            $visit->setDateFin(new \DateTime($dateFin));
        }

        try {
            $entityManager->persist($visit);
            $entityManager->flush();

            return new Response();
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Find customers by NIF for select2 with contacts
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     *
     */
    public function findCustomer(Request $request)
    {
        $query = $request->query->get('q');

        if (!is_null($query)) {
            $customers = $this->getCustomer($query);

            return new JsonResponse($customers);
        }

        return new Response('Not Found', 404);
    }

    /**
     * Find customers by NIF for select2 without contacts
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     *
     */
    public function findCustomerOnly(Request $request)
    {
        $query = $request->query->get('q');

        if (!is_null($query)) {
            $customers = $this->getCustomer($query, false);

            return new JsonResponse($customers);
        }

        return new Response('Not Found', 404);
    }

    /**
     * GET Customer if exists, if customer does not exist and $withContact is true (or null) get Contacts too
     * @param string $query
     * @param bool   $withContact
     *
     * @return mixed
     */
    public function getCustomer(string $query, bool $withContact = true)
    {
        $customers = $this->getDoctrine()->getRepository(Contact::class)->findCustomer($query, 50);
        if (true === !$customers && $withContact) {
            $customers = $this->getDoctrine()->getRepository(Contact::class)->findContactsSelect($query, 50);
        }

        return $customers;
    }

    /**
     * Find providers by NIF for select2
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function findProvider(Request $request)
    {
        $query = $request->query->get('q');

        if (!is_null($query)) {
            $providers = $this->getProvider($query);

            return new JsonResponse($providers);
        }

        return new Response('Not Found', 404);
    }

    /**
     * Find providers by NIF for select2
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function findProviderOnly(Request $request)
    {
        $query = $request->query->get('q');

        if (!is_null($query)) {
            $providers = $this->getProvider($query, false);

            return new JsonResponse($providers);
        }

        return new Response('Not Found', 404);
    }

    /**
     * GET Provider if exists, if not get provider from Contact
     * @param string $query
     * @param bool   $withContact
     *
     * @return mixed
     */
    public function getProvider(string $query, bool $withContact = true)
    {
        $providers = $this->getDoctrine()->getRepository(Contact::class)->findProvider($query, 50);
        if (true === !$providers && $withContact) {
            $providers = $this->getDoctrine()->getRepository(Contact::class)->findContactsSelect($query);
        }

        return $providers;
    }

    /**
     * GET person customer if exists, if not get provider, if not get Contact
     * @param Request $request
     *
     * @return mixed
     */
    public function findPerson(Request $request)
    {
        $query  = $request->request->get('id');
        $person = [];

        //busca cliente
        $customers = $this->getDoctrine()->getRepository(Contact::class)->findCustomer($query, 50);
        if ($customers) {
            foreach ($customers as $customer) {
                $personCustomer = ['id' => $customer['id'], 'text' => $customer['text'], 'type' => 'C'];
                array_push($person, $personCustomer);
            }
        } else {
            $providers = $this->getDoctrine()->getRepository(Contact::class)->findProvider($query, 50);
            if ($providers) {
                foreach ($providers as $provider) {
                    $personProvider = ['id' => $provider['id'], 'text' => $provider['text'], 'type' => 'P'];
                    array_push($person, $personProvider);
                }
            } else {
                $contacts = $this->getDoctrine()->getRepository(Contact::class)->findContactsSelect($query);
                if ($contacts) {
                    foreach ($contacts as $contact) {
                        $personContact = ['id' => $contact['id'], 'text' => $contact['text'], 'type' => 'C'];
                        array_push($person, $personContact);
                    }
                }
            }
        }

        return new JsonResponse($person);
    }

    /**
     * Set status Anulado in visit
     * @param Request $request
     *
     * @return Response
     *
     * @IsGranted("IS_AUTHENTICATED_FULLY", statusCode=401)
     */
    public function cancelVisit(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $statusCancelID = Visit::CANCELED;
        $status         = $this->getDoctrine()->getRepository(Status::class)->findOneBy(['id' => $statusCancelID]);
        $visit          = $this->getDoctrine()->getRepository(Visit::class)->findOneBy(['id' => $request->request->get('idvisit')]);
        $visit->setStatus($status);

        $entityManager->persist($visit);
        $entityManager->flush();

        return new Response();
    }

    /**
     * Obtains the validation errors of a form as an array.
     *
     * @param FormInterface $form Submited form
     *
     * @return array An array of error messages
     */
    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!($child instanceof FormInterface)) {
                continue;
            }
            $childErrors = $this->getFormErrors($child);

            if (empty($childErrors)) {
                continue;
            }
            $name          = $child->getName();
            $errors[$name] = $childErrors;
        }

        return $errors;
    }

    /**
     * This utility method explodes a comma-separated string value of
     * an array into an array of values. Returns an empty array if the
     * key does not exist or is not a valid string.
     *
     * @param string $key    A key in the array
     * @param array  $values Array that contains the value
     *
     * @return array of values
     */
    private function asArray(string $key, array $values): array
    {
        if (key_exists($key, $values)) {
            if (is_string($values[$key])) {
                return explode(',', $values[$key]);
            }
        }

        return [];
    }

    /**
     * Formatea el nombre de la persona
     * @param string $nif
     * @param string $name
     * @param string $typeName
     *
     * @return string
     */
    private function getNamePersonFormatted(string $nif, string $name, string $typeName): string
    {
        switch ($typeName) {
            case 'Customer':
            case 'Customer and supplier':
                $customerType = $this->translator->trans('C');
                break;
            case 'Supplier':
                $customerType = $this->translator->trans('P');
                break;
            case 'Temporary contact':
            default:
                $customerType = $this->translator->trans('Co');
                break;
        }

        $customerText = '['.$nif.'] '.$name.' - '.$customerType;

        return $customerText;
    }
}
