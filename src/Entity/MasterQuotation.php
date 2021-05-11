<?php
/**
 * Entidad MasterQuotation
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterQuotationRepository")
 * @ORM\Table(name="maestra_cotizacion")
 */
class MasterQuotation
{
    /**
     * id
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="canal", type="string", length=255, nullable=true)
     */
    private $canal;

    /**
     * @var string
     *
     * @ORM\Column(name="cuotas", type="string", length=255, nullable=true)
     */
    private $cuotas;

    /**
     * @var int
     *
     * @ORM\Column(name="codigoCoti", type="integer", unique=true)
     */
    private $codigoCoti;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoCampanya", type="string", length=255, nullable=true)
     */
    private $codigoCampanya;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoOficinaAlta", type="string", length=255, nullable=true)
     */
    private $codigoOficinaAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="dictamenTecnico", type="string", length=255, nullable=true)
     */
    private $dictamenTecnico;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="estadoSIA", type="string", length=255, nullable=true)
     */
    private $estadoSIA;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha", type="string", length=255, nullable=true)
     */
    private $fecha;

    /**
     * @var float
     *
     * @ORM\Column(name="financiacionOperacion", type="float", nullable=true)
     */
    private $financiacionOperacion;

    /**
     * @var float
     *
     * @ORM\Column(name="financiacionServicios", type="float", nullable=true)
     */
    private $financiacionServicios;

    /**
     * @var string
     *
     * @ORM\Column(name="gestionInventario", type="string", length=1, nullable=true)
     */
    private $gestionInventario;

    /**
     * @var string
     *
     * @ORM\Column(name="idFamiliaBBEE", type="string", length=255, nullable=true)
     */
    private $idFamiliaBBEE;

    /**
     * @var string
     *
     * @ORM\Column(name="idSubfamiliaBBEE", type="string", length=255, nullable=true)
     */
    private $idSubfamiliaBBEE;

    /**
     * @var string
     *
     * @ORM\Column(name="idSolicitud", type="string", length=255, nullable=true)
     */
    private $idSolicitud;

    /**
     * @var float
     *
     * @ORM\Column(name="inversion", type="float", nullable=true)
     */
    private $inversion;

    /**
     * @var string
     *
     * @ORM\Column(name="lineaProv", type="string", length=255, nullable=true)
     */
    private $lineaProv;

    /**
     * @var float
     *
     * @ORM\Column(name="margenFinanciero", type="float", nullable=true)
     */
    private $margenFinanciero;

    /**
     * @var string
     *
     * @ORM\Column(name="matriculado", type="string", length=1, nullable=true)
     */
    private $matriculado;

    /**
     * @var string
     *
     * @ORM\Column(name="matriculadoCXRProveedor", type="string", length=255, nullable=true)
     */
    private $matriculadoCXRProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="nifCliente", type="string", length=255, nullable=true)
     */
    private $nifCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="nifPactoRecompra", type="string", length=255, nullable=true)
     */
    private $nifPactoRecompra;

    /**
     * @var string
     *
     * @ORM\Column(name="nifPrescriptor", type="string", length=255, nullable=true)
     */
    private $nifPrescriptor;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreCliente", type="string", length=255, nullable=true)
     */
    private $nombreCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="contrato", type="string", length=255, nullable=true)
     */
    private $contrato;

    /**
     * @var int
     *
     * @ORM\Column(name="plazo", type="integer", nullable=true)
     */
    private $plazo;

    /**
     * @var string
     *
     * @ORM\Column(name="producto", type="string", length=255, nullable=true)
     */
    private $producto;

    /**
     * @var string
     *
     * @ORM\Column(name="riesgoTecnico", type="string", length=255, nullable=true)
     */
    private $riesgoTecnico;

    /**
     * @var string
     *
     * @ORM\Column(name="sia", type="string", length=255, nullable=true)
     */
    private $sia;

    /**
     * @var float
     *
     * @ORM\Column(name="tir", type="float", nullable=true)
     */
    private $tir;

    /**
     * @var string
     *
     * @ORM\Column(name="totalCuota", type="string", length=255, nullable=true)
     */
    private $totalCuota;

    /**
     * @var string
     *
     * @ORM\Column(name="valorResidual", type="string", length=255, nullable=true)
     */
    private $valorResidual;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=true)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="vertical", type="string", length=255, nullable=true)
     */
    private $vertical;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCanal(): string
    {
        return $this->canal;
    }

    /**
     * @param string $canal
     */
    public function setCanal(string $canal): void
    {
        $this->canal = $canal;
    }

    /**
     * @return string
     */
    public function getCuotas(): string
    {
        return $this->cuotas;
    }

    /**
     * @param string $cuotas
     */
    public function setCuotas(string $cuotas): void
    {
        $this->cuotas = $cuotas;
    }

    /**
     * @return int
     */
    public function getCodigoCoti(): int
    {
        return $this->codigoCoti;
    }

    /**
     * @param int $codigoCoti
     */
    public function setCodigoCoti(int $codigoCoti): void
    {
        $this->codigoCoti = $codigoCoti;
    }

    /**
     * @return string
     */
    public function getCodigoCampanya(): string
    {
        return $this->codigoCampanya;
    }

    /**
     * @param string $codigoCampanya
     */
    public function setCodigoCampanya(string $codigoCampanya): void
    {
        $this->codigoCampanya = $codigoCampanya;
    }

    /**
     * @return string
     */
    public function getCodigoOficinaAlta(): string
    {
        return $this->codigoOficinaAlta;
    }

    /**
     * @param string $codigoOficinaAlta
     */
    public function setCodigoOficinaAlta(string $codigoOficinaAlta): void
    {
        $this->codigoOficinaAlta = $codigoOficinaAlta;
    }

    /**
     * @return string
     */
    public function getDictamenTecnico(): string
    {
        return $this->dictamenTecnico;
    }

    /**
     * @param string $dictamenTecnico
     */
    public function setDictamenTecnico(string $dictamenTecnico): void
    {
        $this->dictamenTecnico = $dictamenTecnico;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return string
     */
    public function getEstadoSIA(): string
    {
        return $this->estadoSIA;
    }

    /**
     * @param string $estadoSIA
     */
    public function setEstadoSIA(string $estadoSIA): void
    {
        $this->estadoSIA = $estadoSIA;
    }

    /**
     * @return string
     */
    public function getFecha(): string
    {
        return $this->fecha;
    }

    /**
     * @param string $fecha
     */
    public function setFecha(string $fecha): void
    {
        $this->fecha = $fecha;
    }

    /**
     * @return float
     */
    public function getFinanciacionOperacion(): float
    {
        return $this->financiacionOperacion;
    }

    /**
     * @param float $financiacionOperacion
     */
    public function setFinanciacionOperacion(float $financiacionOperacion): void
    {
        $this->financiacionOperacion = $financiacionOperacion;
    }

    /**
     * @return float
     */
    public function getFinanciacionServicios(): float
    {
        return $this->financiacionServicios;
    }

    /**
     * @param float $financiacionServicios
     */
    public function setFinanciacionServicios(float $financiacionServicios): void
    {
        $this->financiacionServicios = $financiacionServicios;
    }

    /**
     * @return string
     */
    public function getGestionInventario(): string
    {
        return $this->gestionInventario;
    }

    /**
     * @param string $gestionInventario
     */
    public function setGestionInventario(string $gestionInventario): void
    {
        $this->gestionInventario = $gestionInventario;
    }

    /**
     * @return string
     */
    public function getIdFamiliaBBEE(): string
    {
        return $this->idFamiliaBBEE;
    }

    /**
     * @param string $idFamiliaBBEE
     */
    public function setIdFamiliaBBEE(string $idFamiliaBBEE): void
    {
        $this->idFamiliaBBEE = $idFamiliaBBEE;
    }

    /**
     * @return string
     */
    public function getIdSubfamiliaBBEE(): string
    {
        return $this->idSubfamiliaBBEE;
    }

    /**
     * @param string $idSubfamiliaBBEE
     */
    public function setIdSubfamiliaBBEE(string $idSubfamiliaBBEE): void
    {
        $this->idSubfamiliaBBEE = $idSubfamiliaBBEE;
    }

    /**
     * @return string
     */
    public function getIdSolicitud(): string
    {
        return $this->idSolicitud;
    }

    /**
     * @param string $idSolicitud
     */
    public function setIdSolicitud(string $idSolicitud): void
    {
        $this->idSolicitud = $idSolicitud;
    }

    /**
     * @return float
     */
    public function getInversion(): float
    {
        return $this->inversion;
    }

    /**
     * @param float $inversion
     */
    public function setInversion(float $inversion): void
    {
        $this->inversion = $inversion;
    }

    /**
     * @return string
     */
    public function getLineaProv(): string
    {
        return $this->lineaProv;
    }

    /**
     * @param string $lineaProv
     */
    public function setLineaProv(string $lineaProv): void
    {
        $this->lineaProv = $lineaProv;
    }

    /**
     * @return float
     */
    public function getMargenFinanciero(): float
    {
        return $this->margenFinanciero;
    }

    /**
     * @param float $margenFinanciero
     */
    public function setMargenFinanciero(float $margenFinanciero): void
    {
        $this->margenFinanciero = $margenFinanciero;
    }

    /**
     * @return string
     */
    public function getMatriculado(): string
    {
        return $this->matriculado;
    }

    /**
     * @param string $matriculado
     */
    public function setMatriculado(string $matriculado): void
    {
        $this->matriculado = $matriculado;
    }

    /**
     * @return string
     */
    public function getMatriculadoCXRProveedor(): string
    {
        return $this->matriculadoCXRProveedor;
    }

    /**
     * @param string $matriculadoCXRProveedor
     */
    public function setMatriculadoCXRProveedor(string $matriculadoCXRProveedor): void
    {
        $this->matriculadoCXRProveedor = $matriculadoCXRProveedor;
    }

    /**
     * @return string
     */
    public function getNifCliente(): string
    {
        return $this->nifCliente;
    }

    /**
     * @param string $nifCliente
     */
    public function setNifCliente(string $nifCliente): void
    {
        $this->nifCliente = $nifCliente;
    }

    /**
     * @return string
     */
    public function getNifPactoRecompra(): string
    {
        return $this->nifPactoRecompra;
    }

    /**
     * @param string $nifPactoRecompra
     */
    public function setNifPactoRecompra(string $nifPactoRecompra): void
    {
        $this->nifPactoRecompra = $nifPactoRecompra;
    }

    /**
     * @return string
     */
    public function getNifPrescriptor(): string
    {
        return $this->nifPrescriptor;
    }

    /**
     * @param string $nifPrescriptor
     */
    public function setNifPrescriptor(string $nifPrescriptor): void
    {
        $this->nifPrescriptor = $nifPrescriptor;
    }

    /**
     * @return string
     */
    public function getNombreCliente(): string
    {
        return $this->nombreCliente;
    }

    /**
     * @param string $nombreCliente
     */
    public function setNombreCliente(string $nombreCliente): void
    {
        $this->nombreCliente = $nombreCliente;
    }

    /**
     * @return string
     */
    public function getContrato(): string
    {
        return $this->contrato;
    }

    /**
     * @param string $contrato
     */
    public function setContrato(string $contrato): void
    {
        $this->contrato = $contrato;
    }

    /**
     * @return int
     */
    public function getPlazo(): int
    {
        return $this->plazo;
    }

    /**
     * @param int $plazo
     */
    public function setPlazo(int $plazo): void
    {
        $this->plazo = $plazo;
    }

    /**
     * @return string
     */
    public function getProducto(): string
    {
        return $this->producto;
    }

    /**
     * @param string $producto
     */
    public function setProducto(string $producto): void
    {
        $this->producto = $producto;
    }

    /**
     * @return string
     */
    public function getRiesgoTecnico(): string
    {
        return $this->riesgoTecnico;
    }

    /**
     * @param string $riesgoTecnico
     */
    public function setRiesgoTecnico(string $riesgoTecnico): void
    {
        $this->riesgoTecnico = $riesgoTecnico;
    }

    /**
     * @return string
     */
    public function getSia(): string
    {
        return $this->sia;
    }

    /**
     * @param string $sia
     */
    public function setSia(string $sia): void
    {
        $this->sia = $sia;
    }

    /**
     * @return float
     */
    public function getTir(): float
    {
        return $this->tir;
    }

    /**
     * @param float $tir
     */
    public function setTir(float $tir): void
    {
        $this->tir = $tir;
    }

    /**
     * @return string
     */
    public function getTotalCuota(): string
    {
        return $this->totalCuota;
    }

    /**
     * @param string $totalCuota
     */
    public function setTotalCuota(string $totalCuota): void
    {
        $this->totalCuota = $totalCuota;
    }

    /**
     * @return string
     */
    public function getValorResidual(): string
    {
        return $this->valorResidual;
    }

    /**
     * @param string $valorResidual
     */
    public function setValorResidual(string $valorResidual): void
    {
        $this->valorResidual = $valorResidual;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVertical(): string
    {
        return $this->vertical;
    }

    /**
     * @param string $vertical
     */
    public function setVertical(string $vertical): void
    {
        $this->vertical = $vertical;
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
     * Establece createdAt
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
        $this->setCanal($row[8]);
        $this->setCuotas($row[15]);
        if (is_numeric($row[17])) {
            $this->setCodigoCoti($row[17]);
        }
        $this->setCodigoCampanya($row[16]);
        $this->setCodigoOficinaAlta((string) $row[21]);
        $this->setDictamenTecnico($row[25]);
        $this->setEstado($row[28]);
        $this->setEstadoSIA($row[30]);
        $this->setFecha($row[32]);
        $this->setFinanciacionOperacion(floatval(str_replace(',', '.', str_replace('.', '', $row[34]))));
        $this->setFinanciacionServicios(floatval(str_replace(',', '.', str_replace('.', '', $row[36]))));
        $this->setGestionInventario($row[37]);
        $this->setIdFamiliaBBEE($row[40]);
        $this->setIdSubfamiliaBBEE($row[41]);
        $this->setIdSolicitud($row[5]);
        $this->setInversion(floatval(str_replace(',', '.', str_replace('.', '', $row[50]))));
        $this->setLineaProv($row[51]);
        $this->setMargenFinanciero(floatval(str_replace(',', '.', str_replace('.', '', $row[53]))));
        $this->setMatriculado($row[55]);
        $this->setMatriculadoCXRProveedor($row[56]);
        $this->setNifCliente((string) $row[58]);
        $this->setNifPactoRecompra($row[59]);
        $this->setNifPrescriptor((string) $row[60]);
        $this->setNombreCliente($row[63]);
        $this->setContrato($row[66]);
        if (is_numeric($row[70])) {
            $this->setPlazo($row[70]);
        }
        $this->setProducto($row[78]);
        $this->setRiesgoTecnico($row[81]);
        $this->setSia($row[85]);
        $this->setTir(floatval(str_replace(',', '.', str_replace('.', '', $row[94]))));
        $this->setTotalCuota($row[98]);
        $this->setValorResidual($row[101]);
        $this->setVersion($row[102]);
        $this->setVertical($row[103]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
