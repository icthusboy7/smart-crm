<?php
/**
 * Entidad Charge
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class charge
 * @ORM\Entity(repositoryClass="App\Repository\ChargeRepository")
 */
class Charge
{
    /**
     * @var int
     * Identificador
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * Nombre
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * Cargo cliente en visita
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="CustomerCharge")
     */
    private $customerVisit;

    /**
     * Cargo proveedor en visita
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="ProviderCharge")
     */
    private $providerVisit;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->customerVisit = new ArrayCollection();
        $this->providerVisit = new ArrayCollection();
    }

    /**
     * charge to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * Obtener el id
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtener el nombre
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Establecer nombre
     * @param string $name
     *
     * @return Charge
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Obtener cargo cliente visita
     *
     * @return ArrayCollection
     */
    public function getCustomerVisit(): ArrayCollection
    {
        return $this->customerVisit;
    }

    /**
     * Añadir cargo cliente visita
     * @param Visit $customerVisit
     *
     * @return Charge
     */
    public function addCustomerVisit(Visit $customerVisit): self
    {
        if (!$this->customerVisit->contains($customerVisit)) {
            $this->customerVisit[] = $customerVisit;
            $customerVisit->setCustomerCharge($this);
        }

        return $this;
    }

    /**
     * Borrar cargo cliente visita
     * @param Visit $customerVisit
     *
     * @return Charge
     */
    public function removeCustomerVisit(Visit $customerVisit): self
    {
        if ($this->customerVisit->contains($customerVisit)) {
            $this->customerVisit->removeElement($customerVisit);
            if ($customerVisit->getCustomerCharge() === $this) {
                $customerVisit->setCustomerCharge(null);
            }
        }

        return $this;
    }

    /**
     * Obtener cargo proveedor visita
     *
     * @return ArrayCollection
     */
    public function getProviderVisit(): ArrayCollection
    {
        return $this->providerVisit;
    }

    /**
     * Añadir cargo proveedor visita
     * @param Visit $providerVisit
     *
     * @return Charge
     */
    public function addProviderVisit(Visit $providerVisit): self
    {
        if (!$this->providerVisit->contains($providerVisit)) {
            $this->providerVisit[] = $providerVisit;
            $providerVisit->setProviderCharge($this);
        }

        return $this;
    }

    /**
     * Borrar cargo proveedor visita
     * @param Visit $providerVisit
     *
     * @return Charge
     */
    public function removeProviderVisit(Visit $providerVisit): self
    {
        if ($this->providerVisit->contains($providerVisit)) {
            $this->providerVisit->removeElement($providerVisit);
            if ($providerVisit->getProviderCharge() === $this) {
                $providerVisit->setProviderCharge(null);
            }
        }

        return $this;
    }
}
