<?php
/**
 * Entidad MasterCustomer
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterCustomerRepository")
 * @ORM\Table(name="maestra_cliente", indexes={
 *   @ORM\Index(name="nif_idx", columns={"nif"})
 * })
 */
class MasterCustomer
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
     * Comunidad autonoma de cliente
     *
     * @var string
     *
     * @ORM\Column(name="comunidadAutonoma", type="string", length=255, nullable=true)
     */
    private $comunidadAutonomaCliente;

    /**
     * Codigo linea cliente
     *
     * @var string
     *
     * @ORM\Column(name="codigoLinea", type="bigint", nullable=true)
     */
    private $codigoLineaCliente;

    /**
     * Codigo oficina procedencia cliente
     *
     *  @var string
     *
     * @ORM\Column(name="codigoOficinaProcedencia", type="integer", nullable=true)
     */
    private $codigoOficinaProcedenciaCliente;

    /**
     * Codigo postal cliente
     *
     *  @var string
     *
     * @ORM\Column(name="codigoPostal", type="integer", nullable=true)
     */
    private $codigoPostalCliente;

    /**
     * Disponible inversión linia de cliente
     *
     *  @var string
     *
     * @ORM\Column(name="disponibleInversionLinea", type="integer", nullable=true)
     */
    private $disponibleInversionLineaCliente;

    /**
     * Disposición inversión linia de cliente
     *
     *  @var string
     *
     * @ORM\Column(name="dispuestoInversionLinea", type="integer", nullable=true)
     */
    private $dispuestoInversionLineaCliente;

    /**
     * Estado de linia de cliente
     *
     * @var string
     *
     * @ORM\Column(name="estadoLinea", type="string", length=255, nullable=true)
     */
    private $estadoLineaCliente;

    /**
     * Grupo cliente
     *
     *  @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255, nullable=true)
     */
    private $grupoCliente;

    /**
     * Grupo juridico cliente
     *
     *  @var string
     *
     * @ORM\Column(name="grupoJuridico", type="string", length=255, nullable=true)
     */
    private $grupoJuridico;

    /**
     * Limite de inversión en la linia de cliente
     *
     * @var string
     *
     * @ORM\Column(name="limiteInversionLinea", type="integer", nullable=true)
     */
    private $limiteInversionLineaCliente;

    /**
     * NIF cliente
     *
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255, nullable=true)
     */
    private $nif;

    /**
     * Nombre cliente
     *
     *  @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * Población cliente
     *
     *  @var string
     *
     * @ORM\Column(name="poblacion", type="string", length=255, nullable=true)
     */
    private $poblacion;

    /**
     * Provincia cliente
     *
     *  @var string
     *
     * @ORM\Column(name="provincia", type="string", length=255, nullable=true)
     */
    private $provincia;

    /**
     * Tipo de cartera caixabank
     *
     *  @var string
     *
     * @ORM\Column(name="tipo_cartera_cxb", type="string", length=255, nullable=true)
     */
    private $tipoCarteraCxb;

    /**
     * Gestor del cliente caixabank
     *
     * @var string
     *
     * @ORM\Column(name="tipo_gestor_cxb", type="integer", nullable=true)
     */
    private $gestorClienteCxb;

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
    public function getComunidadAutonomaCliente(): string
    {
        return $this->comunidadAutonomaCliente;
    }

    /**
     * @param string $comunidadAutonomaCliente
     */
    public function setComunidadAutonomaCliente(string $comunidadAutonomaCliente): void
    {
        $this->comunidadAutonomaCliente = $comunidadAutonomaCliente;
    }

    /**
     * @return string
     */
    public function getCodigoLineaCliente(): string
    {
        return $this->codigoLineaCliente;
    }

    /**
     * @param string $codigoLineaCliente
     */
    public function setCodigoLineaCliente(string $codigoLineaCliente): void
    {
        $this->codigoLineaCliente = $codigoLineaCliente;
    }

    /**
     * @return string
     */
    public function getCodigoOficinaProcedenciaCliente(): string
    {
        return $this->codigoOficinaProcedenciaCliente;
    }

    /**
     * @param string $codigoOficinaProcedenciaCliente
     */
    public function setCodigoOficinaProcedenciaCliente(string $codigoOficinaProcedenciaCliente): void
    {
        $this->codigoOficinaProcedenciaCliente = $codigoOficinaProcedenciaCliente;
    }

    /**
     * @return string
     */
    public function getCodigoPostalCliente(): string
    {
        return $this->codigoPostalCliente;
    }

    /**
     * @param string $codigoPostalCliente
     */
    public function setCodigoPostalCliente(string $codigoPostalCliente): void
    {
        $this->codigoPostalCliente = $codigoPostalCliente;
    }

    /**
     * @return string
     */
    public function getDisponibleInversionLineaCliente(): string
    {
        return $this->disponibleInversionLineaCliente;
    }

    /**
     * @param string $disponibleInversionLineaCliente
     */
    public function setDisponibleInversionLineaCliente(string $disponibleInversionLineaCliente): void
    {
        $this->disponibleInversionLineaCliente = $disponibleInversionLineaCliente;
    }

    /**
     * @return string
     */
    public function getDispuestoInversionLineaCliente(): string
    {
        return $this->dispuestoInversionLineaCliente;
    }

    /**
     * @param string $dispuestoInversionLineaCliente
     */
    public function setDispuestoInversionLineaCliente(string $dispuestoInversionLineaCliente): void
    {
        $this->dispuestoInversionLineaCliente = $dispuestoInversionLineaCliente;
    }

    /**
     * @return string
     */
    public function getEstadoLineaCliente(): string
    {
        return $this->estadoLineaCliente;
    }

    /**
     * @param string $estadoLineaCliente
     */
    public function setEstadoLineaCliente(string $estadoLineaCliente): void
    {
        $this->estadoLineaCliente = $estadoLineaCliente;
    }

    /**
     * @return string
     */
    public function getGrupoCliente(): string
    {
        return $this->grupoCliente;
    }

    /**
     * @param string $grupoCliente
     */
    public function setGrupoCliente(string $grupoCliente): void
    {
        $this->grupoCliente = $grupoCliente;
    }

    /**
     * @return string
     */
    public function getGrupoJuridico(): string
    {
        return $this->grupoJuridico;
    }

    /**
     * @param string $grupoJuridico
     */
    public function setGrupoJuridico(string $grupoJuridico): void
    {
        $this->grupoJuridico = $grupoJuridico;
    }

    /**
     * @return string
     */
    public function getLimiteInversionLineaCliente(): string
    {
        return $this->limiteInversionLineaCliente;
    }

    /**
     * @param string $limiteInversionLineaCliente
     */
    public function setLimiteInversionLineaCliente(string $limiteInversionLineaCliente): void
    {
        $this->limiteInversionLineaCliente = $limiteInversionLineaCliente;
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
     * @return string
     */
    public function getPoblacion(): string
    {
        return $this->poblacion;
    }

    /**
     * @param string $poblacion
     */
    public function setPoblacion(string $poblacion): void
    {
        $this->poblacion = $poblacion;
    }

    /**
     * @return string
     */
    public function getProvincia(): string
    {
        return $this->provincia;
    }

    /**
     * @param string $provincia
     */
    public function setProvincia(string $provincia): void
    {
        $this->provincia = $provincia;
    }

    /**
     * @return string
     */
    public function getTipoCarteraCxb(): string
    {
        return $this->tipoCarteraCxb;
    }

    /**
     * @param string $tipoCarteraCxb
     */
    public function setTipoCarteraCxb(string $tipoCarteraCxb): void
    {
        $this->tipoCarteraCxb = $tipoCarteraCxb;
    }

    /**
     * @return string
     */
    public function getGestorClienteCxb(): string
    {
        return $this->gestorClienteCxb;
    }

    /**
     * @param string $gestorClienteCxb
     */
    public function setGestorClienteCxb(string $gestorClienteCxb): void
    {
        $this->gestorClienteCxb = $gestorClienteCxb;
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
        $this->setComunidadAutonomaCliente($row[9]);
        if (is_numeric($row[11])) {
            $this->setCodigoLineaCliente($row[11]);
        }
        if (is_numeric($row[12])) {
            $this->setCodigoOficinaProcedenciaCliente($row[12]);
        }
        if (is_numeric($row[13])) {
            $this->setCodigoPostalCliente($row[13]);
        }
        if (is_numeric($row[14])) {
            $this->setDisponibleInversionLineaCliente($row[14]);
        }
        if (is_numeric($row[15])) {
            $this->setDispuestoInversionLineaCliente($row[15]);
        }
        $this->setEstadoLineaCliente($row[16]);
        $this->setGrupoCliente($row[19]);
        $this->setGrupoJuridico($row[20]);
        if (is_numeric($row[24])) {
            $this->setLimiteInversionLineaCliente($row[24]);
        }
        $this->setNif((string) $row[25]);
        $this->setNombre($row[26]);
        $this->setPoblacion($row[29]);
        $this->setProvincia($row[30]);
        $this->setTipoCarteraCxb($row[33]);
        if (is_numeric($row[17])) {
            $this->setGestorClienteCxb($row[17]);
        }
        $this->setCreatedAt(new DateTime('now'));
    }
}
