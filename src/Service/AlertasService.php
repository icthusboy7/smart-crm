<?php

namespace App\Service;

use \Exception;
use App\Entity\ComercialAlertas;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialTask;
use App\Entity\MasterOffice;
use App\Repository\ComercialAlertasRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AlertasService
 */
class AlertasService
{

    /**
     * @var object
     */
    private $em;

    /**
     * @var ComercialAlertasRepository
     */
    private $alertas;

    /**
     * AlertasService constructor.
     * @param EntityManagerInterface $em
     *
     * @throws Exception
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em      = $em;
        $this->alertas = $em->getRepository(ComercialAlertas::class);
    }

    /**
     * Send number of Nifs with alerts
     *
     * @param string $nif
     *
     * @return array
     */
    public function getAlertsNIF(string $nif): array
    {
        return $this->em->getRepository(ComercialAlertas::class)->findBy(['personaNif' => $nif, 'active' => true]);
    }

    /**
     * Send number of offices with alerts
     *
     * @param string $office
     *
     * @return array
     */
    public function getAlertsOffice(string $office): array
    {
        return $this->em->getRepository(ComercialAlertas::class)->findBy(['oficina' => $office, 'active' => true]);
    }

    /**
     * Return data of alerts of expedientes or cotizaciones
     *
     * @param array  $toSearch
     * @param string $typeAlert
     *
     * @return array
     */
    public function getMuroAlerts(array $toSearch, string $typeAlert): array
    {
        $inCotizaciones = [];
        $expediente     = null;
        $office         = null;
        if (is_null($toSearch['idExpediente']) && !is_null($toSearch['idTarea'])) {
            $task = $this->em->getRepository(ComercialTask::class)->findOneBy(['id' => $toSearch['idTarea']]);
            if (!is_null($task->getComercialMuro())) {
                $toSearch['idExpediente']       = !is_null($task->getComercialMuro()->getExpediente())
                    ? $task->getComercialMuro()->getExpediente()->getId()
                    : $toSearch['idExpediente'] = '';
            } else {
                $toSearch['idExpediente'] = '';
            }
        }

        if ($toSearch['idExpediente']) {
            $expediente = $this->em->getRepository(ComercialExpediente::class)->find($toSearch['idExpediente']);


            foreach ($expediente->getCotizaciones() as $cotizacion) {
                $inCotizaciones[] = $cotizacion->getCotizacion();
            }
        }

        if (!is_null($expediente)) {
            $office = $this->em->getRepository(MasterOffice::class)->findOneBy(['codigo' => $expediente->getOficina()]);
        }

        return [
            'pipelines'  => $this->alertas->findAlerts('expediente', (is_null($expediente)
                ? null : $expediente->getId()), $typeAlert),
            'quotes'     => $this->alertas->findAlerts('cotizacion', $inCotizaciones, $typeAlert),
            'customers'  => $this->alertas->findAlerts('personaNif', (is_null($expediente)
                ? null : $expediente->getClienteNIF()), $typeAlert),
            'providers'  => $this->alertas->findAlerts('personaNif', (is_null($expediente)
                ? null : $expediente->getPrescriptorCIF()), $typeAlert),
            'offices'    => $this->alertas->findAlerts('oficina', $office, $typeAlert),
            'horizontal' => $this->alertas->findAlerts('horizontal', (is_null($expediente)
                ? null : $expediente->getCanal()), $typeAlert),
            'vertical'   => $this->alertas->findAlerts('vertical', (is_null($expediente)
                ? null : $expediente->getVertical()), $typeAlert),
        ];
    }

    /**
     * Return total count alerts and notices
     * @param array $notices
     * @param array $alerts
     *
     * @return array
     */
    public function countTotalAlerts(array $notices, array $alerts): array
    {
        $totalCount = [];

        $totalCount['offices']   = $this->countAlerts('offices', $alerts['offices'], $notices['offices']);
        $totalCount['customers'] = $this->countAlerts('customers', $alerts['customers'], $notices['customers']);
        $totalCount['providers'] = $this->countAlerts('providers', $alerts['providers'], $notices['providers']);

        return $totalCount;
    }

    /**
     * Count Alerts
     * @param string $type
     * @param array  $alerts
     * @param array  $notices
     *
     * @return int
     */
    public function countAlerts(string $type, array $alerts, array $notices): int
    {
        $total = 0;
        if ('offices' === $type) {
            $total = count($alerts) + count($notices);
            foreach ($alerts as $alert) {
                if (is_null($alert->getChildOfficeAlerts())) {
                    continue;
                }
                $total += count($alert->getChildOfficeAlerts());
            }

            foreach ($notices as $notice) {
                if (is_null($notice->getChildOfficeAlerts())) {
                    continue;
                }
                $total += count($notice->getChildOfficeAlerts());
            }

            return $total;
        }

        return count($alerts) + count($notices);
    }

    /**
     * @param string $idOffice
     *
     * @return array|null
     */
    public function hasChildOffice(string $idOffice): ?array
    {
        $childrenOffice = $this->em->getRepository(MasterOffice::class)->findOfficeHeritage($idOffice);

        if (count($childrenOffice) > 0) {
            foreach ($childrenOffice as $childOffice) {
                $this->hasChildOffice($childOffice->getCodigo());
            }

            return $childrenOffice;
        }

        return null;
    }
}
