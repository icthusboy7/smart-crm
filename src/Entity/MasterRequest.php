<?php
/**
 * Entidad MasterRequest
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterRequestRepository")
 * @ORM\Table(name="maestra_solicitud")
 */
class MasterRequest
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
     * @ORM\Column(name="apoderado1", type="string", length=255, nullable=true)
     */
    private $apoderadoPrimario;

    /**
     * @var string
     *
     * @ORM\Column(name="apoderado2", type="string", length=255, nullable=true)
     */
    private $apoderadoSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="aval_prescriptor", type="string", length=1, nullable=true)
     */
    private $avalPrescriptor;

    /**
     * @var string
     *
     * @ORM\Column(name="avales", type="string", length=1, nullable=true)
     */
    private $avales;

    /**
     * @var string
     *
     * @ORM\Column(name="canal_solicitud", type="string", length=255, nullable=true)
     */
    private $canalSolicitud;

    /**
     * @var string
     *
     * @ORM\Column(name="centro_sia", type="string", length=255, nullable=true)
     */
    private $centroSIA;

    /**
     * @var string
     *
     * @ORM\Column(name="check_opcion_compra", type="string", length=1, nullable=true)
     */
    private $checkOpcionCompra;

    /**
     * @var string
     *
     * @ORM\Column(name="check_pacto_recompra", type="string", length=1, nullable=true)
     */
    private $checkPactoRecompra;

    /**
     * @var string
     *
     * @ORM\Column(name="circuito_operacion_detalle", type="string", length=255, nullable=true)
     */
    private $circuitoOperacionDetalle;

    /**
     * @var string
     *
     * @ORM\Column(name="circuito_operacion", type="string", length=255, nullable=true)
     */
    private $circuitoOperacion;

    /**
     * @var string
     *
     * @ORM\Column(name="confort_letter", type="string", length=1, nullable=true)
     */
    private $confortLetter;

    /**
     * @var string
     *
     * @ORM\Column(name="contrato_telefonica", type="string", length=255, nullable=true)
     */
    private $contratoTelefonica;

    /**
     * @var string
     *
     * @ORM\Column(name="contrato_tfn", type="string", length=255, nullable=true)
     */
    private $contratoTFN;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_cotizacion", type="string", length=255, nullable=true)
     */
    private $codigoCotizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_oficina_alta", type="string", length=255, nullable=true)
     */
    private $codigoOficinaAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="motivo_cancelacion", type="string", length=255, nullable=true)
     */
    private $motivoCancelacion;

    /**
     * @var string
     *
     * @ORM\Column(name="submotivo_cancelacion", type="string", length=255, nullable=true)
     */
    private $submotivoCancelacion;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle_estado", type="string", length=255, nullable=true)
     */
    private $detalleEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle_forma", type="string", length=255, nullable=true)
     */
    private $detalleForma;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_padre", type="string", length=255, nullable=true)
     */
    private $docPadre;

    /**
     * @var string
     *
     * @ORM\Column(name="entrega_inicial", type="string", length=1, nullable=true)
     */
    private $entregaInicial;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_actual_general", type="string", length=255, nullable=true)
     */
    private $estadoActualGeneral;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_actual_general_ajustado", type="string", length=255, nullable=true)
     */
    private $estadoActualGeneralAjustado;

    /**
     * @var string
     *
     * @ORM\Column(name="estado_actual", type="string", length=255, nullable=true)
     */
    private $estadoActual;

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
    public function getApoderadoPrimario(): string
    {
        return $this->apoderadoPrimario;
    }

    /**
     * @param string $apoderadoPrimario
     */
    public function setApoderadoPrimario(string $apoderadoPrimario): void
    {
        $this->apoderadoPrimario = $apoderadoPrimario;
    }

    /**
     * @return string
     */
    public function getApoderadoSecundario(): string
    {
        return $this->apoderadoSecundario;
    }

    /**
     * @param string $apoderadoSecundario
     */
    public function setApoderadoSecundario(string $apoderadoSecundario): void
    {
        $this->apoderadoSecundario = $apoderadoSecundario;
    }

    /**
     * @return string
     */
    public function getAvalPrescriptor(): string
    {
        return $this->avalPrescriptor;
    }

    /**
     * @param string $avalPrescriptor
     */
    public function setAvalPrescriptor(string $avalPrescriptor): void
    {
        $this->avalPrescriptor = $avalPrescriptor;
    }

    /**
     * @return string
     */
    public function getAvales(): string
    {
        return $this->avales;
    }

    /**
     * @param string $avales
     */
    public function setAvales(string $avales): void
    {
        $this->avales = $avales;
    }

    /**
     * @return string
     */
    public function getCanalSolicitud(): string
    {
        return $this->canalSolicitud;
    }

    /**
     * @param string $canalSolicitud
     */
    public function setCanalSolicitud(string $canalSolicitud): void
    {
        $this->canalSolicitud = $canalSolicitud;
    }

    /**
     * @return string
     */
    public function getCentroSIA(): string
    {
        return $this->centroSIA;
    }

    /**
     * @param string $centroSIA
     */
    public function setCentroSIA(string $centroSIA): void
    {
        $this->centroSIA = $centroSIA;
    }

    /**
     * @return string
     */
    public function getCheckOpcionCompra(): string
    {
        return $this->checkOpcionCompra;
    }

    /**
     * @param string $checkOpcionCompra
     */
    public function setCheckOpcionCompra(string $checkOpcionCompra): void
    {
        $this->checkOpcionCompra = $checkOpcionCompra;
    }

    /**
     * @return string
     */
    public function getCheckPactoRecompra(): string
    {
        return $this->checkPactoRecompra;
    }

    /**
     * @param string $checkPactoRecompra
     */
    public function setCheckPactoRecompra(string $checkPactoRecompra): void
    {
        $this->checkPactoRecompra = $checkPactoRecompra;
    }

    /**
     * @return string
     */
    public function getCircuitoOperacionDetalle(): string
    {
        return $this->circuitoOperacionDetalle;
    }

    /**
     * @param string $circuitoOperacionDetalle
     */
    public function setCircuitoOperacionDetalle(string $circuitoOperacionDetalle): void
    {
        $this->circuitoOperacionDetalle = $circuitoOperacionDetalle;
    }

    /**
     * @return string
     */
    public function getCircuitoOperacion(): string
    {
        return $this->circuitoOperacion;
    }

    /**
     * @param string $circuitoOperacion
     */
    public function setCircuitoOperacion(string $circuitoOperacion): void
    {
        $this->circuitoOperacion = $circuitoOperacion;
    }

    /**
     * @return string
     */
    public function getConfortLetter(): string
    {
        return $this->confortLetter;
    }

    /**
     * @param string $confortLetter
     */
    public function setConfortLetter(string $confortLetter): void
    {
        $this->confortLetter = $confortLetter;
    }

    /**
     * @return string
     */
    public function getContratoTelefonica(): string
    {
        return $this->contratoTelefonica;
    }

    /**
     * @param string $contratoTelefonica
     */
    public function setContratoTelefonica(string $contratoTelefonica): void
    {
        $this->contratoTelefonica = $contratoTelefonica;
    }

    /**
     * @return string
     */
    public function getContratoTFN(): string
    {
        return $this->contratoTFN;
    }

    /**
     * @param string $contratoTFN
     */
    public function setContratoTFN(string $contratoTFN): void
    {
        $this->contratoTFN = $contratoTFN;
    }

    /**
     * @return string
     */
    public function getCodigoCotizacion(): string
    {
        return $this->codigoCotizacion;
    }

    /**
     * @param string $codigoCotizacion
     */
    public function setCodigoCotizacion(string $codigoCotizacion): void
    {
        $this->codigoCotizacion = $codigoCotizacion;
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
    public function getMotivoCancelacion(): string
    {
        return $this->motivoCancelacion;
    }

    /**
     * @param string $motivoCancelacion
     */
    public function setMotivoCancelacion(string $motivoCancelacion): void
    {
        $this->motivoCancelacion = $motivoCancelacion;
    }

    /**
     * @return string
     */
    public function getSubmotivoCancelacion(): string
    {
        return $this->submotivoCancelacion;
    }

    /**
     * @param string $submotivoCancelacion
     */
    public function setSubmotivoCancelacion(string $submotivoCancelacion): void
    {
        $this->submotivoCancelacion = $submotivoCancelacion;
    }

    /**
     * @return string
     */
    public function getDetalleEstado(): string
    {
        return $this->detalleEstado;
    }

    /**
     * @param string $detalleEstado
     */
    public function setDetalleEstado(string $detalleEstado): void
    {
        $this->detalleEstado = $detalleEstado;
    }

    /**
     * @return string
     */
    public function getDetalleForma(): string
    {
        return $this->detalleForma;
    }

    /**
     * @param string $detalleForma
     */
    public function setDetalleForma(string $detalleForma): void
    {
        $this->detalleForma = $detalleForma;
    }

    /**
     * @return string
     */
    public function getDocPadre(): string
    {
        return $this->docPadre;
    }

    /**
     * @param string $docPadre
     */
    public function setDocPadre(string $docPadre): void
    {
        $this->docPadre = $docPadre;
    }

    /**
     * @return string
     */
    public function getEntregaInicial(): string
    {
        return $this->entregaInicial;
    }

    /**
     * @param string $entregaInicial
     */
    public function setEntregaInicial(string $entregaInicial): void
    {
        $this->entregaInicial = $entregaInicial;
    }

    /**
     * @return string
     */
    public function getEstadoActualGeneral(): string
    {
        return $this->estadoActualGeneral;
    }

    /**
     * @param string $estadoActualGeneral
     */
    public function setEstadoActualGeneral(string $estadoActualGeneral): void
    {
        $this->estadoActualGeneral = $estadoActualGeneral;
    }

    /**
     * @return string
     */
    public function getEstadoActualGeneralAjustado(): string
    {
        return $this->estadoActualGeneralAjustado;
    }

    /**
     * @param string $estadoActualGeneralAjustado
     */
    public function setEstadoActualGeneralAjustado(string $estadoActualGeneralAjustado): void
    {
        $this->estadoActualGeneralAjustado = $estadoActualGeneralAjustado;
    }

    /**
     * @return string
     */
    public function getEstadoActual(): string
    {
        return $this->estadoActual;
    }

    /**
     * @param string $estadoActual
     */
    public function setEstadoActual(string $estadoActual): void
    {
        $this->estadoActual = $estadoActual;
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
        $this->setApoderadoPrimario($row[12]);
        $this->setApoderadoSecundario($row[13]);
        $this->setAvalPrescriptor($row[24]);
        $this->setAvales($row[25]);
        $this->setCanalSolicitud($row[28]);
        $this->setCentroSIA($row[30]);
        $this->setCheckOpcionCompra($row[31]);
        $this->setCheckPactoRecompra($row[32]);
        $this->setCircuitoOperacionDetalle($row[33]);
        $this->setCircuitoOperacion($row[34]);
        $this->setConfortLetter($row[36]);
        $this->setContratoTelefonica($row[37]);
        $this->setContratoTFN($row[38]);
        $this->setCodigoCotizacion($row[40]);
        $this->setCodigoOficinaAlta((string) $row[41]);
        $this->setMotivoCancelacion($row[42]);
        $this->setSubmotivoCancelacion($row[43]);
        $this->setDetalleEstado($row[44]);
        $this->setDetalleForma($row[45]);
        $this->setDocPadre($row[46]);
        $this->setEntregaInicial($row[53]);
        $this->setEstadoActualGeneral($row[54]);
        $this->setEstadoActualGeneralAjustado($row[55]);
        $this->setEstadoActual($row[56]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
