<?php

namespace App\Service;

use App\Core\Entity\Contact;
use App\Entity\MasterCustomer;
use App\Entity\MasterProvider;

use Doctrine\ORM\EntityManagerInterface;

class DataService
{
    protected const NAMECUSTOMER = 'NOMBRECLIENTE';
    protected const NAMEPROVIDER = 'NOMBREPROVEEDOR';
    protected const MOTIVOACCION = 'MOTIVOACCION';
    public const VCREATEDAT      = 'vCreatedAt';
    public const VUPDATEDAT      = 'vUpdatedAt';
    public const AUTORVISIT      = 'AutorVisit';
    public const NIVEL           = 'NIVEL';
    public const GRUPO           = 'GRUPO';
    public const PETLINEAID      = 'PETLINEAID';
    public const TAREANOTI       = 'TAREANOTI';
    public const MOTIVOCANC      = 'MOTIVOCANC';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * DataService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }
    /**
     * @param array $fields
     *
     * @return string
     */
    public function headTitles(array $fields): string
    {
        return strtoupper(implode($fields, '|')).PHP_EOL;
    }

    /**
     * @param array  $data
     * @param array  $fields
     * @param string $typeClass
     *
     * @return string
     */
    public function getFields(array $data, array $fields, string $typeClass): string
    {
        $response = '';
        foreach ($data as $res) {
            foreach ($fields as $key => $field) {
                if ($this->isSpecialField($field)) {
                    $response .= $this->specialField($typeClass, $field, $res);
                } elseif (is_object($res->{('get'.$field)}())) {
                    $response .= $this->getDataEntity($res->{('get'.$field)}()).'|';
                } else {
                    if (!is_null($res->{('get'.$field)}())) {
                        $response .= $res->{('get'.$field)}().'|';
                    } else {
                        $response .= '|';
                    }
                }
            }
            $response .= PHP_EOL;
        }

        return $response;
    }

    /**
     * @param array  $data
     * @param array  $fields
     * @param string $typeClass
     *
     * @return string
     */
    public function getFieldsQuote(array $data, array $fields, string $typeClass): string
    {
        $response = '';
        foreach ($data as $res) {
            foreach ($fields as $key => $field) {
                if ('ExpedienteID' === $field) {
                    $response .= $res['id'].'|';
                } elseif ('fechacreacioncoti' === $field) {
                    if (is_null($res['fechaEstado'])) {
                        $response .= '|';
                    } elseif (get_class($res['fechaEstado']) === 'DateTime') {
                        $response .= $res['fechaEstado']->format('d/m/Y').'|';
                    } else {
                        $response .= $res['fechaEstado'].'|';
                    }
                } elseif (is_object($res[$field]) && get_class($res[$field]) === 'DateTime') {
                    $response .= $res[$field]->format('d/m/Y').'|';
                } else {
                    $response .= $res[$field].'|';
                }
            }
            $response .= PHP_EOL;
        }

        return $response;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function isSpecialField(string $field): bool
    {
        return (in_array(
            $field,
            [
                self::VCREATEDAT,
                self::MOTIVOACCION,
                self::NAMEPROVIDER,
                self::NAMECUSTOMER,
                self::VUPDATEDAT,
                self::AUTORVISIT,
                self::NIVEL,
                self::GRUPO,
                'Expediente',
                'Cotización',
                'AutorTask',
                self::PETLINEAID,
                self::TAREANOTI,
                self::MOTIVOCANC,
            ]
        )
        ) ? true : false;
    }
    /**
     * @param string $typeClass
     * @param string $field
     * @param object $res
     *
     * @return string|null
     */
    public function specialField(string $typeClass, string $field, object $res): ?string
    {
        switch ($typeClass) {
            case 'pipelinesDump':
                if (self::NAMECUSTOMER === $field) {
                    $value = $this->getDataName(['nif' => $res->getClienteNif()], MasterCustomer::class).'|';
                } elseif (self::NAMEPROVIDER === $field) {
                    $value = $this->getDataName(['nif' => $res->getPrescriptorCif()], MasterProvider::class).'|';
                } else {
                    $value = '';
                }

                return $value;
                break;
            case 'visitDump':
                if (self::NAMECUSTOMER === $field) {
                    $val = $this->getDataName(['nif' => $res->getCustomerID()], Contact::class);
                    if ('' === $val) {
                        $val = $this->getDataName(['nif' => $res->getCustomerID()], MasterCustomer::class);
                    }
                    $value = $val.'|';
                } elseif (self::NAMEPROVIDER === $field) {
                    $val = $this->getDataName(['nif' => $res->getProviderID()], Contact::class);
                    if ('' === $val) {
                        $val = $this->getDataName(['nif' => $res->getCustomerID()], MasterProvider::class);
                    }
                    $value = $val.'';
                } else {
                    $value = '';
                }

                return $value;
                break;
            case 'taskDump':
                $value = '';

                if ('Expediente' === $field) {
                    if (!is_null($res->getComercialMuro())) {
                        if (!is_null($res->getComercialMuro()->getExpediente())) {
                            $value = $res->getComercialMuro()->getExpediente()->getId().'|';
                        }
                    }
                }
                if ('Cotización' === $field) {
                    if (!is_null($res->getComercialMuro())) {
                        $value = $res->getComercialMuro()->getCotizacion().'|';
                    }
                }
                if ('AutorTask' === $field) {
                    if (!is_null($res->getComercialMuro())) {
                        $value = $res->getComercialMuro()->getAutor()->getRegNumber().'|';
                    }
                }
                if (in_array($field, [self::PETLINEAID, self::TAREANOTI])) {
                    $value = '|';
                }
                if ('MOTIVOCANC' === $field) {
                    if (!is_null($res->getComercialMuro())) {
                        if (!is_null($res->getComercialMuro()->getCerradoPor())) {
                            $value = $res->getComercialMuro()->getCerradoPor()->getMissatge();
                        }
                    }
                }

                return $value;
                break;
            default:
                return '|';
        }
    }
    /**
     * @param array  $search
     * @param string $entityToSearch
     *
     * @return string
     */
    public function getDataName(array $search, string $entityToSearch): string
    {
        return (!is_null($result = $this->em->getRepository($entityToSearch)->findOneBy($search)))
            ? $result->getNombre()
            : '';
    }

    /**
     * @param object $class
     * @param string $typeClass
     *
     * @return array
     */
    public function getFieldsTitles(object $class, string $typeClass): array
    {
        $response = [];
        foreach (get_class_methods($class) as $methods) {
            (strpos($methods, 'get') === 0 && $this->isSettingInTitles($typeClass, $methods))
                ? $response[] = substr($methods, 3)
                : null;
        }

        if (in_array($typeClass, ['pipelinesDump', 'visitDump', ])) {
            $response[] = self::NAMECUSTOMER;
            $response[] = self::NAMEPROVIDER;
        }

        return $response;
    }

    /**
     * @param string $typeClass
     * @param string $methods
     *
     * @return bool
     */
    public function isSettingInTitles(string $typeClass, string $methods): bool
    {
        if ('pipelinesDump' === $typeClass && in_array($methods, ['getAlertas', 'getDocuments'])) {
            return false;
        }
        if ('visitDump' === $typeClass && in_array($methods, ['getCustomerChargeAnother', 'getProviderChargeAnother'])) {
            return false;
        }

        return true;
    }

    /**
     * @param object $fields
     *
     * @return string
     */
    public function getDataEntity(object $fields): string
    {
        if (get_class($fields) === 'Proxies\__CG__\App\Entity\TipoCanal') {
            return $fields->getCanalDesc();
        }
        if (get_class($fields) === 'App\Entity\User' || get_class($fields) === 'Proxies\__CG__\App\Entity\User') {
            return $fields->getRegNumber();
        }
        if (get_class($fields) === 'Proxies\__CG__\App\Entity\ComercialExpedienteStatus') {
            return $fields->getStatus();
        }
        if (get_class($fields) === 'Proxies\__CG__\App\Entity\Vertical' || get_class($fields) === 'Proxies\__CG__\App\Entity\Charge' || get_class($fields) === 'Proxies\__CG__\App\Entity\Status') {
            return $fields->getName();
        }
        if (get_class($fields) === 'Proxies\__CG__\App\Entity\ComercialProducto') {
            return $fields->getNombre();
        }
        if (get_class($fields) === 'DateTime') {
            return $fields->format('d/m/Y');
        }
        if (get_class($fields) === 'Proxies\__CG__\App\Entity\ComercialTaskStatus' || get_class($fields) === 'Proxies\__CG__\App\Entity\ComercialTaskType') {
            return $fields->getDescription();
        }

        return '';
    }
}
