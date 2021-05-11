<?php


namespace App\Controller;

use App\Core\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SelectController
 *
 * @package App\Controller
 */
class SelectController extends AbstractController
{

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    private $_em;

    /**
     * SelectController constructor.
     *
     * @param EntityManagerInterface $em Entity manager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
    }
    /**
     * Find customers by NIF for select2 without contacts
     *
     * @param Request $request Select2 find
     *
     * @return JsonResponse|Response
     */
    public function findPersons(Request $request)
    {
        try {
            $person = $this->_em->getRepository(Contact::class)
                ->allContactsSelect2($request->query->get('q'), 50);

            if (is_null($person)) {
                return new Response('Not Found', 404);
            }

        } catch (\Exception $e) {
            return new Response('Not Found', 404);
        }

        return new JsonResponse($person);
    }
}
