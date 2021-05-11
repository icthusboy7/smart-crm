<?php
/**
 * Entidad Alertas
 */

namespace App\Entity;

use \DateTime;
use \Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ComercialAlertas
 * @ORM\Table(name="comercial_alertas")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialAlertasRepository")
 */
class ComercialAlertas
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="missatge", type="text")
     */
    private $missatge;

    /**
     * @var int
     *
     * @ORM\Column(name="nivel", type="integer")
     */
    private $nivel;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_alert", type="boolean")
     */
    private $isAlert = false;

    /**
     * @var int
     *
     * @ORM\Column(name="deleted", type="integer", nullable=true)
     */
    private $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="deletedby", type="string", nullable=true)
     */
    private $deletedBy;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var ComercialExpediente
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialExpediente", inversedBy="alertas")
     */
    private $expediente;

    /**
     * @var string
     *
     * @ORM\Column(name="cotizacion", type="string", length=20, nullable=true)
     */
    private $cotizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="personanif", type="string", length=20, nullable=true)
     */
    private $personaNif;

    /**
     * @var string
     *
     * @ORM\Column(name="oficina", type="string", length=255, nullable=true)
     */
    private $oficina;

    /**
     * @var Horizontal
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Horizontal")
     */
    private $horizontal;

    /**
     * @var Vertical
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Vertical")
     */
    private $vertical;


    /**
     * @var childOfficeAlerts;
     */
    private $childOfficeAlerts;
    /**
     * Alertas constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->active    = false;
        $this->isAlert   = false;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * Tranform integer to string
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->id;
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
     * Set missatge
     *
     * @param string $missatge
     *
     * @return ComercialAlertas
     *
     */
    public function setMissatge(string $missatge): self
    {
        $this->missatge = $missatge;

        return $this;
    }

    /**
     * Get missatge
     *
     * @return string
     */
    public function getMissatge(): string
    {
        return (is_null($this->missatge) ? '' : $this->missatge);
    }

    /**
     * Set nivel
     *
     * @param int $nivel
     *
     * @return ComercialAlertas
     */
    public function setNivel(int $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param int $active
     *
     * @return ComercialAlertas
     */
    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsAlert(): bool
    {
        return $this->isAlert;
    }

    /**
     * @param bool $isAlert
     *
     * @return ComercialAlertas
     */
    public function setIsAlert(bool $isAlert): self
    {
        $this->isAlert = $isAlert;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return int
     */
    public function getNivel(): int
    {
        return (is_null($this->nivel) ? 0 : $this->nivel);
    }

    /**
     * Set deleted
     *
     * @param int $deleted
     *
     * @return ComercialAlertas
     */
    public function setDeleted(int $deleted): self
    {
        $this->deleted = (is_null($deleted) ? 0 : $deleted);

        return $this;
    }

    /**
     * Get Deleted
     *
     * @return string
     */
    public function getDeleted(): string
    {
        return (is_null($this->deleted) ? 0 : $this->deleted);
    }

    /**
     * Set deletedBy
     *
     * @param string $deletedBy
     *
     * @return ComercialAlertas
     */
    public function setDeletedBy(string $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return string|null
     */
    public function getDeletedBy(): string
    {
        return (is_null($this->deletedBy) ? 0 : $this->deletedBy);
    }

    /**
     * Set createdAt
     *
     * @param DateTime $deletedAt
     *
     * @return ComercialAlertas
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return DateTime|null
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }
    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ComercialAlertas
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return ComercialAlertas
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Get Expediente
     *
     * @return ComercialExpediente|null
     */
    public function getExpediente(): ?ComercialExpediente
    {
        return $this->expediente;
    }

    /**
     * Set Expediente
     *
     * @param ComercialExpediente|null $expediente
     *
     * @return ComercialAlertas
     */
    public function setExpediente(?ComercialExpediente $expediente): self
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get Cotizacion
     *
     * @return string|null
     */
    public function getCotizacion(): string
    {
        return (is_null($this->cotizacion) ? '' : $this->cotizacion);
    }

    /**
     * Set Cotizacion
     * @param string|null $cotizacion
     *
     * @return ComercialAlertas
     */
    public function setCotizacion(?string $cotizacion): self
    {
        $this->cotizacion = (is_null($cotizacion) ? '' : $cotizacion);

        return $this;
    }

    /**
     * Set oficina
     *
     * @param string $oficina
     *
     * @return ComercialAlertas
     */
    public function setOficina(?string $oficina): self
    {
        $this->oficina = (is_null($oficina) ? '' : $oficina);

        return $this;
    }

    /**
     * Get oficina
     *
     * @return string
     */
    public function getOficina(): string
    {
        return (is_null($this->oficina) ? '' : $this->oficina);
    }

    /**
     * Set personaNif
     *
     * @param string|null $personaNif
     *
     * @return ComercialAlertas
     */
    public function setPersonaNif(?string $personaNif): self
    {
        $this->personaNif = $personaNif;

        return $this;
    }

    /**
     * Get personaNif
     *
     * @return string|null
     */
    public function getPersonaNif(): ?string
    {
        return $this->personaNif;
    }

    /**
     * Set horizontal
     *
     * @param Horizontal|null $horizontal
     *
     * @return ComercialAlertas
     */
    public function setHorizontal(?Horizontal $horizontal): self
    {
        $this->horizontal = $horizontal;

        return $this;
    }

    /**
     * Get horizontal
     *
     * @return Horizontal|null
     */
    public function getHorizontal(): ?Horizontal
    {
        return $this->horizontal;
    }

    /**
     * Set vertical
     *
     * @param Vertical|null $vertical
     *
     * @return ComercialAlertas
     */
    public function setVertical(?Vertical $vertical): self
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Get Vertical
     *
     * @return Vertical|null
     */
    public function getVertical(): ?Vertical
    {
        return $this->vertical;
    }

    /**
     * Set Offices child
     * @param array|null $child
     */
    public function setChildOfficeAlerts(?array $child): void
    {
        $this->childOfficeAlerts = $child;
    }


    /**
     * @return array|null
     */
    public function getChildOfficeAlerts(): ?array
    {
        return $this->childOfficeAlerts;
    }
}
