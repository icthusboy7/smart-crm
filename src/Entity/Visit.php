<?php
/**
 * Entidad Visit
 */
namespace App\Entity;

use App\Entity\Concerns\HasAuthor;
use App\Entity\Concerns\HasTimestamps;
use App\Entity\Traits\HasAuthorTrait;
use App\Entity\Traits\HasTimestampsTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Visit
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 */
class Visit implements HasAuthor, HasTimestamps
{
    use HasAuthorTrait, HasTimestampsTrait;

    /** If visit is a pending visit */
    public const PENDING = 1;

    /** If visit is a done visit */
    public const DONE = 2;

    /** If visit is a canceled visit */
    public const CANCELED = 3;

    /**
     * Identificador de la visita
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * Identificador del cliente
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CustomerID;

    /**
     * Cargo del cliente
     * @ORM\ManyToOne(targetEntity="App\Entity\Charge", inversedBy="customerVisit")
     */
    private $CustomerCharge;

    /**
     * Otro Cargo del cliente
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CustomerChargeAnother;

    /**
     * Identificador del proveedor
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ProviderID;

    /**
     * Cargo del proveedor
     * @ORM\ManyToOne(targetEntity="App\Entity\Charge", inversedBy="providerVisit")
     */
    private $ProviderCharge;

    /**
     * Otro cargo del proveedor
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ProviderChargeAnother;

    /**
     * Oficina
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $office;

    /**
     * Motivo de la visita
     * @ORM\ManyToOne(targetEntity="App\Entity\Vertical", inversedBy="visit")
     */
    private $vertical;

    /**
     * Motivo de la visita
     * @ORM\ManyToOne(targetEntity="App\Entity\Reason", inversedBy="visit")
     */
    private $reason;

    /**
     * Fecha inicial
     * @ORM\Column(type="datetime")
     */
    private $DateIni;

    /**
     * Fecha final
     * @ORM\Column(type="datetime")
     */
    private $DateFin;

    /**
     * Duración
     * @ORM\Column(type="string", length=5)
     */
    private $duration;

    /**
     * Motivo de la visita
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="visit")
     */
    private $status;

    /**
     * Tipo de visita
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * Observaciones
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;

    /**
     * Feedback
     * @ORM\Column(type="text", nullable=true)
     */
    private $feedback;

    /**
     * Obtiene el Identificador de la visita
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Obtiene el identificador del cliente
     * @return mixed
     */
    public function getCustomerID()
    {
        return $this->CustomerID;
    }

    /**
     * Setea el identificador del cliente
     * @param mixed $CustomerID
     *
     * @return Visit
     */
    public function setCustomerID($CustomerID): self
    {
        $this->CustomerID = $CustomerID;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerCharge()
    {
        return $this->CustomerCharge;
    }

    /**
     * @param mixed $CustomerCharge
     *
     * @return Visit
     */
    public function setCustomerCharge($CustomerCharge): self
    {
        $this->CustomerCharge = $CustomerCharge;

        return $this;
    }

    /**
     * obtiene el cargo otro del cliente
     * @return mixed
     */
    public function getCustomerChargeAnother()
    {
        return $this->CustomerChargeAnother;
    }

    /**
     * setea el cargo otro del cliente
     * @param mixed $CustomerChargeAnother
     *
     * @return Visit
     */
    public function setCustomerChargeAnother($CustomerChargeAnother): self
    {
        $this->CustomerChargeAnother = $CustomerChargeAnother;

        return $this;
    }

    /**
     * obtiene el identificador del proveedor
     * @return mixed
     */
    public function getProviderID()
    {
        return $this->ProviderID;
    }

    /**
     * setea el identificador del proveedor
     * @param mixed $ProviderID
     *
     * @return Visit
     */
    public function setProviderID($ProviderID): self
    {
        $this->ProviderID = $ProviderID;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProviderCharge()
    {
        return $this->ProviderCharge;
    }

    /**
     * @param mixed $ProviderCharge
     *
     * @return Visit
     */
    public function setProviderCharge($ProviderCharge): self
    {
        $this->ProviderCharge = $ProviderCharge;

        return $this;
    }

    /**
     * obtiene el cargo otro del proveedor
     * @return mixed
     */
    public function getProviderChargeAnother()
    {
        return $this->ProviderChargeAnother;
    }

    /**
     * setea el cargo otro del proveedor
     * @param mixed $ProviderChargeAnother
     *
     * @return Visit
     */
    public function setProviderChargeAnother($ProviderChargeAnother): self
    {
        $this->ProviderChargeAnother = $ProviderChargeAnother;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param mixed $office
     *
     * @return Visit
     */
    public function setOffice($office): self
    {
        $this->office = $office;

        return $this;
    }

    /**
     * obtiene el vertical
     * @return mixed
     */
    public function getVertical()
    {
        return $this->vertical;
    }

    /**
     * setea el vertical
     * @param mixed $vertical
     *
     * @return Visit
     */
    public function setVertical($vertical): self
    {
        $this->vertical = $vertical;

        return $this;
    }

    /**
     * Obtiene el motivo de la visita
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * setea el motivo de la visita
     * @param mixed $reason
     *
     * @return Visit
     */
    public function setReason($reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Obtiene la fecha inicial
     * @return \DateTimeInterface|null
     */
    public function getDateIni(): ?\DateTimeInterface
    {
        return $this->DateIni;
    }

    /**
     * setea la fecha inicial
     * @param \DateTimeInterface $DateIni
     * @return Visit
     */
    public function setDateIni(\DateTimeInterface $DateIni): self
    {
        $this->DateIni = $DateIni;

        return $this;
    }

    /**
     * obtiene la fecna final
     * @return \DateTimeInterface|null
     */
    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    /**
     * setea la fecha final
     * @param \DateTimeInterface $DateFin
     *
     * @return Visit
     */
    public function setDateFin(\DateTimeInterface $DateFin): self
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     *
     * @return Visit
     */
    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return Visit
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * obtiene el tipo
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setea el tipo
     * @param mixed $type
     *
     * @return Visit
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * obtiene las observaciones
     * @return mixed
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * setea las observaciones
     * @param mixed $observations
     *
     * @return Visit
     */
    public function setObservations($observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * obtiene el feedback
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * setea el feedback
     * @param mixed $feedback
     *
     * @return Visit
     */
    public function setFeedback($feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }
}
