<?php
/**
 * Entidad MasterOffice
 */

namespace App\Entity;

use \DateTime;
use \DateTimeInterface;
use \Exception;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MasterOfficeRepository")
 * @ORM\Table(name="maestra_oficina")
 */
class MasterOffice
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
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;


    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255, nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="dt", type="string", length=255, nullable=true)
     */
    private $dt;

    /**
     * @var string
     *
     * @ORM\Column(name="dg", type="string", length=255, nullable=true)
     */
    private $dg;

    /**
     * @var string
     *
     * @ORM\Column(name="dan", type="string", length=255, nullable=true)
     */
    private $dan;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=255, nullable=true)
     */
    private $domicilio;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=255, nullable=true)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="poblacion", type="string", length=255, nullable=true)
     */
    private $poblacion;

    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", length=255, nullable=true)
     */
    private $provincia;

    /**
     * @var string
     *
     * @ORM\Column(name="comunidad", type="string", length=255, nullable=true)
     */
    private $comunidad;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255, nullable=true)
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono1", type="string", length=255, nullable=true)
     */
    private $telefonoPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono2", type="string", length=255, nullable=true)
     */
    private $telefonoSecundario;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=255, nullable=true)
     */
    private $categoria;

    /**
     * @var int
     *
     * @ORM\Column(name="centroActualActivoCounter", type="integer", nullable=true)
     */
    private $centroActualActivoCounter;

    /**
     * @var int
     *
     * @ORM\Column(name="centroActualActivo", type="integer", nullable=true)
     */
    private $centroActualActivo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoSubtipo",type="string", length=255, nullable=true)
     */
    private $codigoSubtipo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoTipo",type="string", length=255, nullable=true)
     */
    private $codigoTipo;

    /**
     * @var string
     *
     * @ORM\Column(name="directorActual",type="string", length=255, nullable=true)
     */
    private $directorActual;

    /**
     * @var string
     *
     * @ORM\Column(name="gestorActual",type="string", length=255, nullable=true)
     */
    private $gestorActual;

    /**
     * @var string
     *
     * @ORM\Column(name="fechaAlta",type="string", length=255, nullable=true)
     */
    private $fechaAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="fechaBaja",type="string", length=255, nullable=true)
     */
    private $fechaBaja;

    /**
     * @var string
     *
     * @ORM\Column(name="segmentoComercial",type="string", length=255, nullable=true)
     */
    private $segmentoComercial;

    /**
     * @var string
     *
     * @ORM\Column(name="subddirectorActual",type="string", length=255, nullable=true)
     */
    private $subddirectorActual;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones",type="string", length=1500, nullable=true)
     */
    private $observaciones;

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
    public function getCodigo(): string
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     */
    public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
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
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getDt(): string
    {
        return $this->dt;
    }

    /**
     * @param string $dt
     */
    public function setDt(string $dt): void
    {
        $this->dt = $dt;
    }

    /**
     * @return string
     */
    public function getDg(): string
    {
        return $this->dg;
    }

    /**
     * @param string $dg
     */
    public function setDg(string $dg): void
    {
        $this->dg = $dg;
    }

    /**
     * @return string
     */
    public function getDan(): string
    {
        return $this->dan;
    }

    /**
     * @param string $dan
     */
    public function setDan(string $dan): void
    {
        $this->dan = $dan;
    }

    /**
     * @return string
     */
    public function getDomicilio(): string
    {
        return $this->domicilio;
    }

    /**
     * @param string $domicilio
     */
    public function setDomicilio(string $domicilio): void
    {
        $this->domicilio = $domicilio;
    }

    /**
     * @return string
     */
    public function getCp(): string
    {
        return $this->cp;
    }

    /**
     * @param string $cp
     */
    public function setCp(string $cp): void
    {
        $this->cp = $cp;
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
    public function getComunidad(): string
    {
        return $this->comunidad;
    }

    /**
     * @param string $comunidad
     */
    public function setComunidad(string $comunidad): void
    {
        $this->comunidad = $comunidad;
    }

    /**
     * @return string
     */
    public function getPais(): string
    {
        return $this->pais;
    }

    /**
     * @param string $pais
     */
    public function setPais(string $pais): void
    {
        $this->pais = $pais;
    }

    /**
     * @return string
     */
    public function getTelefonoPrincipal(): string
    {
        return $this->telefonoPrincipal;
    }

    /**
     * @param string $telefonoPrincipal
     */
    public function setTelefonoPrincipal(string $telefonoPrincipal): void
    {
        $this->telefonoPrincipal = $telefonoPrincipal;
    }

    /**
     * @return string
     */
    public function getTelefonoSecundario(): string
    {
        return $this->telefonoSecundario;
    }

    /**
     * @param string $telefonoSecundario
     */
    public function setTelefonoSecundario(string $telefonoSecundario): void
    {
        $this->telefonoSecundario = $telefonoSecundario;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCategoria(): string
    {
        return $this->categoria;
    }

    /**
     * @param string $categoria
     */
    public function setCategoria(string $categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * @return int
     */
    public function getCentroActualActivoCounter(): int
    {
        return $this->centroActualActivoCounter;
    }

    /**
     * @param int $centroActualActivoCounter
     */
    public function setCentroActualActivoCounter(int $centroActualActivoCounter): void
    {
        $this->centroActualActivoCounter = $centroActualActivoCounter;
    }

    /**
     * @return int
     */
    public function getCentroActualActivo(): int
    {
        return $this->centroActualActivo;
    }

    /**
     * @param int $centroActualActivo
     */
    public function setCentroActualActivo(int $centroActualActivo): void
    {
        $this->centroActualActivo = $centroActualActivo;
    }

    /**
     * @return string
     */
    public function getCodigoSubtipo(): string
    {
        return $this->codigoSubtipo;
    }

    /**
     * @param string $codigoSubtipo
     */
    public function setCodigoSubtipo(string $codigoSubtipo): void
    {
        $this->codigoSubtipo = $codigoSubtipo;
    }

    /**
     * @return string
     */
    public function getCodigoTipo(): string
    {
        return $this->codigoTipo;
    }

    /**
     * @param string $codigoTipo
     */
    public function setCodigoTipo(string $codigoTipo): void
    {
        $this->codigoTipo = $codigoTipo;
    }

    /**
     * @return string
     */
    public function getDirectorActual(): string
    {
        return $this->directorActual;
    }

    /**
     * @param string $directorActual
     */
    public function setDirectorActual(string $directorActual): void
    {
        $this->directorActual = $directorActual;
    }

    /**
     * @return string
     */
    public function getGestorActual(): string
    {
        return $this->gestorActual;
    }

    /**
     * @param string $gestorActual
     */
    public function setGestorActual(string $gestorActual): void
    {
        $this->gestorActual = $gestorActual;
    }

    /**
     * @return string
     */
    public function getFechaAlta(): string
    {
        return $this->fechaAlta;
    }

    /**
     * @param string $fechaAlta
     */
    public function setFechaAlta(string $fechaAlta): void
    {
        $this->fechaAlta = $fechaAlta;
    }

    /**
     * @return string
     */
    public function getFechaBaja(): string
    {
        return $this->fechaBaja;
    }

    /**
     * @param string $fechaBaja
     */
    public function setFechaBaja(string $fechaBaja): void
    {
        $this->fechaBaja = $fechaBaja;
    }

    /**
     * @return string
     */
    public function getSegmentoComercial(): string
    {
        return $this->segmentoComercial;
    }

    /**
     * @param string $segmentoComercial
     */
    public function setSegmentoComercial(string $segmentoComercial): void
    {
        $this->segmentoComercial = $segmentoComercial;
    }

    /**
     * @return string
     */
    public function getSubddirectorActual(): string
    {
        return $this->subddirectorActual;
    }

    /**
     * @param string $subddirectorActual
     */
    public function setSubddirectorActual(string $subddirectorActual): void
    {
        $this->subddirectorActual = $subddirectorActual;
    }

    /**
     * @return string
     */
    public function getObservaciones(): string
    {
        return $this->observaciones;
    }

    /**
     * @param string $observaciones
     */
    public function setObservaciones(string $observaciones): void
    {
        $this->observaciones = $observaciones;
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
     * @param int   $columnIni
     *
     * @throws Exception
     */
    public function setData(array $row, int $columnIni): void
    {
        $this->setCodigo((string) $row[$columnIni]);
        $this->setNombre($row[2]);
        $this->setTipo($row[12]);
        $this->setDt($row[14]);
        $this->setDg($row[13]);
        $this->setDan($row[15]);
        $this->setDomicilio($row[17]);
        $this->setCp($row[10]);
        $this->setPoblacion($row[32]);
        $this->setProvincia($row[33]);
        $this->setComunidad($row[6]);
        $this->setPais($row[31]);
        $this->setTelefonoPrincipal($row[36]);
        $this->setTelefonoSecundario($row[37]);
        $this->setEmail($row[18]);
        $this->setCategoria($row[1]);
        if (is_numeric($row[3])) {
            $this->setCentroActualActivoCounter($row[3]);
        }
        if (is_numeric($row[3])) {
            $this->setCentroActualActivo($row[4]);
        }
        $this->setCodigoSubtipo($row[11]);
        $this->setCodigoTipo($row[12]);
        $this->setDirectorActual($row[16]);
        $this->setGestorActual($row[24]);
        $this->setFechaAlta($row[21]);
        $this->setFechaBaja($row[22]);
        $this->setSegmentoComercial($row[34]);
        $this->setSubddirectorActual($row[35]);
        $this->setCreatedAt(new DateTime('now'));
    }
}
