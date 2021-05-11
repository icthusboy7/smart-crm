<?php
/**
 * Entidad MasterProvider
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterProviderRepository")
 * @ORM\Table(name="maestra_proveedor", indexes={
 *   @ORM\Index(name="nif_idx", columns={"nif"})
 * })
 */
class MasterProvider
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * NIF proveedor
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nif;

    /**
     * @var string
     * Nombre Proveedor
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * Codigo Acreedor Prescriptor
     *
     * @var int
     *
     * @ORM\Column(name="codigo_acreedor", type="integer", nullable=true)
     */
    private $codigoAcreedorPrescriptor;

    /**
     * Codigo Comercial Oficina Actual Prescriptor
     *
     * @var int
     *
     * @ORM\Column(name="codigo_comercial_oficina_actual", type="integer", nullable=true)
     */
    private $codigoComercialOficinaActualPrescriptor;

    /**
     * Fecha Carterizacion
     *
     * @var string
     *
     * @ORM\Column(name="fecha_carterizacion", type="string", length=255, nullable=true)
     */
    private $fechaCarterizacion;

    /**
     * Grupo Empresas Prescriptor
     *
     * @var string
     *
     * @ORM\Column(name="grupo_empresas", type="string", length=255, nullable=true)
     */
    private $grupoEmpresasPrescriptor;

    /**
     * Oficina Prescriptor
     *
     * @var string
     *
     * @ORM\Column(name="oficina_prescriptor", type="string", length=255, nullable=true)
     */
    private $oficinaPrescriptor;

    /**
     * Prescriptor Primario
     *
     * @var string
     *
     * @ORM\Column(name="prescriptor_primario", type="string", length=255, nullable=true)
     */
    private $prescriptorPrimario;

    /**
     * Prescriptor Primario Nombre
     *
     * @var string
     *
     * @ORM\Column(name="prescriptor_primario_nombre", type="string", length=255, nullable=true)
     */
    private $prescriptorPrimarioNombre;

    /**
     * Tipo Prescriptor
     *
     * @var string
     *
     * @ORM\Column(name="tipo_prescriptor", type="string", length=255, nullable=true)
     */
    private $tipoPrescriptor;

    /**
     * Tipo Prescriptor Detalle
     *
     * @var string
     *
     * @ORM\Column(name="tipo_prescriptor_detalle", type="string", length=255, nullable=true)
     */
    private $tipoPrescriptorDetalle;

    /**
     * Prescriptor Carterizado
     *
     * @var string
     *
     * @ORM\Column(name="carterizado", type="string", length=255, nullable=true)
     */
    private $prescriptorCarterizado;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNif(): string
    {
        return $this->nif;
    }

    /**
     * @param string $nif
     */
    public function setNif(string $nif): void
    {
        $this->nif = $nif;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return int
     */
    public function getCodigoAcreedorPrescriptor(): int
    {
        return $this->codigoAcreedorPrescriptor;
    }

    /**
     * @param int $codigoAcreedorPrescriptor
     */
    public function setCodigoAcreedorPrescriptor(int $codigoAcreedorPrescriptor): void
    {
        $this->codigoAcreedorPrescriptor = $codigoAcreedorPrescriptor;
    }

    /**
     * @return int
     */
    public function getCodigoComercialOficinaActualPrescriptor(): int
    {
        return $this->codigoComercialOficinaActualPrescriptor;
    }

    /**
     * @param int $codigoComercialOficinaActualPrescriptor
     */
    public function setCodigoComercialOficinaActualPrescriptor(int $codigoComercialOficinaActualPrescriptor): void
    {
        $this->codigoComercialOficinaActualPrescriptor = $codigoComercialOficinaActualPrescriptor;
    }

    /**
     * @return string
     */
    public function getFechaCarterizacion(): string
    {
        return $this->fechaCarterizacion;
    }

    /**
     * @param string $fechaCarterizacion
     */
    public function setFechaCarterizacion(string $fechaCarterizacion): void
    {
        $this->fechaCarterizacion = $fechaCarterizacion;
    }

    /**
     * @return string
     */
    public function getGrupoEmpresasPrescriptor(): string
    {
        return $this->grupoEmpresasPrescriptor;
    }

    /**
     * @param string $grupoEmpresasPrescriptor
     */
    public function setGrupoEmpresasPrescriptor(string $grupoEmpresasPrescriptor): void
    {
        $this->grupoEmpresasPrescriptor = $grupoEmpresasPrescriptor;
    }

    /**
     * @return string
     */
    public function getOficinaPrescriptor(): string
    {
        return $this->oficinaPrescriptor;
    }

    /**
     * @param string $oficinaPrescriptor
     */
    public function setOficinaPrescriptor(string $oficinaPrescriptor): void
    {
        $this->oficinaPrescriptor = $oficinaPrescriptor;
    }

    /**
     * @return string
     */
    public function getPrescriptorPrimario(): string
    {
        return $this->prescriptorPrimario;
    }

    /**
     * @param string $prescriptorPrimario
     */
    public function setPrescriptorPrimario(string $prescriptorPrimario): void
    {
        $this->prescriptorPrimario = $prescriptorPrimario;
    }

    /**
     * @return string
     */
    public function getPrescriptorPrimarioNombre(): string
    {
        return $this->prescriptorPrimarioNombre;
    }

    /**
     * @param string $prescriptorPrimarioNombre
     */
    public function setPrescriptorPrimarioNombre(string $prescriptorPrimarioNombre): void
    {
        $this->prescriptorPrimarioNombre = $prescriptorPrimarioNombre;
    }

    /**
     * @return string
     */
    public function getTipoPrescriptor(): string
    {
        return $this->tipoPrescriptor;
    }

    /**
     * @param string $tipoPrescriptor
     */
    public function setTipoPrescriptor(string $tipoPrescriptor): void
    {
        $this->tipoPrescriptor = $tipoPrescriptor;
    }

    /**
     * @return string
     */
    public function getTipoPrescriptorDetalle(): string
    {
        return $this->tipoPrescriptorDetalle;
    }

    /**
     * @param string $tipoPrescriptorDetalle
     */
    public function setTipoPrescriptorDetalle(string $tipoPrescriptorDetalle): void
    {
        $this->tipoPrescriptorDetalle = $tipoPrescriptorDetalle;
    }

    /**
     * @return string
     */
    public function getPrescriptorCarterizado(): string
    {
        return $this->prescriptorCarterizado;
    }

    /**
     * @param string $prescriptorCarterizado
     */
    public function setPrescriptorCarterizado(string $prescriptorCarterizado): void
    {
        $this->prescriptorCarterizado = $prescriptorCarterizado;
    }

    /**
     * obtiene createdAt
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Establece CreatedAt
     *
     * @param DateTimeInterface|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param array $row
     *
     * @throws Exception
     */
    public function setData(array $row): void
    {
        $this->setNif((string) $row[22]);
        $this->setNombre($row[23]);
        if (is_numeric($row[7])) {
            $this->setCodigoAcreedorPrescriptor($row[7]);
        }
        if (is_numeric($row[8])) {
            $this->setCodigoComercialOficinaActualPrescriptor($row[8]);
        }
        $this->setFechaCarterizacion($row[18]);
        $this->setGrupoEmpresasPrescriptor($row[21]);
        $this->setOficinaPrescriptor($row[24]);
        $this->setPrescriptorPrimario($row[31]);
        $this->setPrescriptorPrimarioNombre($row[32]);
        $this->setTipoPrescriptor($row[41]);
        $this->setTipoPrescriptorDetalle($row[42]);
        $this->setPrescriptorCarterizado($row[45]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
