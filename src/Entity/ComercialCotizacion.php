<?php
/**
 * Entidad ComercialCotización
 */

namespace App\Entity;

use \DateTime;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ComercialCotización
 * @ORM\Entity(repositoryClass="App\Repository\ComercialCotizacionRepository")
 */
class ComercialCotizacion
{
    /**
     * @var int
     *
     * Identificador
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="numCoti", type="string", length=20, unique=true)
     */
    private $numCoti;

    /**
     * @var string
     *
     * @ORM\Column(name="plazo", type="string", length=10)
     */
    private $plazo;

    /**
     * @var float
     *
     * @ORM\Column(name="cuota", type="float", nullable=true)
     */
    private $cuota;

    /**
     * @var float
     *
     * @ORM\Column(name="inversion", type="float", nullable=true)
     */
    private $inversion;

    /**
     * @var string
     *
     * @ORM\Column(name="indicaciones", type="text", nullable=true)
     */
    private $indicaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @var array
     *
     * @ORM\Column(name="tareas", type="json_array", nullable=true)
     */
    private $tareas;

    /**
     * @var array
     *
     * @ORM\Column(name="checklist", type="json_array", nullable=true)
     */
    private $checklist;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="fechaEstado", type="datetime", nullable=true)
     */
    private $fechaEstado;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha_tarea", type="string", length=255, nullable=true)
     */
    private $fechaTarea;

    /**
     * @var string
     *
     * @ORM\Column(name="id_tarea", type="string", length=20, nullable=true)
     */
    private $idTarea;

    /**
     * @var string
     *
     * @ORM\Column(name="autor", type="string", length=20, nullable=true)
     */
    private $autor;

    /**
     * @var string
     *
     * @ORM\Column(name="solicitante_nombre", type="string", length=255, nullable=true)
     */
    private $solicitanteNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="solicitante_nif", type="string", length=20, nullable=true)
     */
    private $solicitanteNif;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setCreatedValues(): void
    {
        $this->updatedAt = new DateTime('now');
    }

    /**
     * ComercialCotizacion constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now');
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->id;
    }

    /**
     * SetId
     *
     * @param int $id
     *
     * @return ComercialCotizacion
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set numCoti
     *
     * @param string $numCoti
     *
     * @return ComercialCotizacion
     */
    public function setNumCoti(string $numCoti): self
    {
        $this->numCoti = $numCoti;

        return $this;
    }

    /**
     * Get numCoti
     *
     * @return string
     */
    public function getNumCoti(): string
    {
        return $this->numCoti;
    }

    /**
     * Set plazo
     *
     * @param string $plazo
     *
     * @return ComercialCotizacion
     */
    public function setPlazo(string $plazo): self
    {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * Get plazo
     *
     * @return string
     */
    public function getPlazo(): string
    {
        return $this->plazo;
    }

    /**
     * Set cuota
     *
     * @param float $cuota
     *
     * @return ComercialCotizacion
     */
    public function setCuota(?float $cuota): self
    {
        $this->cuota = $cuota;

        return $this;
    }

    /**
     * Get cuota
     *
     * @return float
     */
    public function getCuota(): ?float
    {
        return $this->cuota;
    }

    /**
     * Set inversion
     *
     * @param float $inversion
     *
     * @return ComercialCotizacion
     */
    public function setInversion(float $inversion): self
    {
        $this->inversion = $inversion;

        return $this;
    }

    /**
     * Get inversion
     *
     * @return float
     */
    public function getInversion(): float
    {
        return $this->inversion;
    }

    /**
     * Set indicaciones
     *
     * @param string $indicaciones
     *
     * @return ComercialCotizacion
     */
    public function setIndicaciones(string $indicaciones): self
    {
        $this->indicaciones = $indicaciones;

        return $this;
    }

    /**
     * Get indicaciones
     *
     * @return string
     */
    public function getIndicaciones(): string
    {
        return $this->indicaciones;
    }

    /**
     * Set estado
     *
     * @param string|null $estado
     *
     * @return ComercialCotizacion
     */
    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string|null
     */
    public function getEstado(): ?string
    {
        return $this->estado;
    }

    /**
     * Set fechaEstado
     *
     * @param DateTime $fechaEstado
     *
     * @return ComercialCotizacion
     */
    public function setFechaEstado(DateTime $fechaEstado): self
    {
        $this->fechaEstado = $fechaEstado;

        return $this;
    }

    /**
     * Get fechaEstado
     *
     * @return DateTime
     */
    public function getFechaEstado(): DateTime
    {
        return $this->fechaEstado;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ComercialCotizacion
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ComercialCotizacion
     */
    public function setUpdatedAt(\Datetime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    /**
     * Set tareas
     *
     * @param array $tareas
     *
     * @return ComercialCotizacion
     */
    public function setTareas(array $tareas): self
    {
        $this->tareas = $tareas;

        return $this;
    }

    /**
     * Get tareas
     *
     * @return array
     */
    public function getTareas(): array
    {
        return $this->tareas;
    }

    /**
     * Set checklist
     *
     * @param array $checklist
     *
     * @return ComercialCotizacion
     */
    public function setChecklist(array $checklist): self
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * Get checklist
     *
     * @return array
     */
    public function getChecklist(): array
    {
        return $this->checklist;
    }

    /**
     * @return string|null
     */
    public function getFechaTarea(): ?string
    {
        return $this->fechaTarea;
    }

    /**
     * @param string|null $fechaTarea
     *
     * @return ComercialCotizacion
     */
    public function setFechaTarea(?string $fechaTarea): self
    {
        $this->fechaTarea = $fechaTarea;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdTarea(): int
    {
        return $this->idTarea;
    }

    /**
     * @param int $idTarea
     *
     * @return ComercialCotizacion
     */
    public function setIdTarea(int $idTarea): self
    {
        $this->idTarea = $idTarea;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutor(): string
    {
        return $this->autor;
    }

    /**
     * @param string $autor
     *
     * @return ComercialCotizacion
     */
    public function setAutor(string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * @return string
     */
    public function getSolicitanteNombre(): string
    {
        return $this->solicitanteNombre;
    }

    /**
     * @param string $solicitanteNombre
     *
     * @return ComercialCotizacion
     */
    public function setSolicitanteNombre(string $solicitanteNombre): self
    {
        $this->solicitanteNombre = $solicitanteNombre;

        return $this;
    }

    /**
     * @return string
     */
    public function getSolicitanteNif(): string
    {
        return $this->solicitanteNif;
    }

    /**
     * @param string $solicitanteNif
     *
     * @return ComercialCotizacion
     */
    public function setSolicitanteNif(string $solicitanteNif): self
    {
        $this->solicitanteNif = $solicitanteNif;

        return $this;
    }
}
