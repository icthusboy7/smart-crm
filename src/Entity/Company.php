<?php
/**
 * Entidad Company
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Company
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * Identificador
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Nombre de compañia
     * @var string
     *
     * @ORM\Column(name="companyName", type="string", length=255, unique=true)
     */
    private $companyName;

    /**
     * Nombre abreviado de compañia
     * @var string
     *
     * @ORM\Column(name="companyShort", type="string", length=50, unique=true)
     */
    private $companyShort;

    /**
     * Numero de registro
     * @ORM\OneToMany(targetEntity="User", mappedBy="company")
     */
    private $regNumber;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->regNumber = new ArrayCollection();
    }

    /**
     * Company to string
     * @return string
     */
    public function __toString()
    {
        return (string)$this->companyName;
    }

    /**
     * Obtener id de compañia
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtener nombre de compañia
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Establecer nombre de compañia
     * @param string $companyName
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * Obtener nombre abreviado de compañia
     * @return string
     */
    public function getCompanyShort()
    {
        return $this->companyShort;
    }

    /**
     * Establecer nombre abreviado de compañia
     * @param string $companyShort
     */
    public function setCompanyShort(string $companyShort): void
    {
        $this->companyShort = $companyShort;
    }

    /**
     * Obtener numero de registro
     * @return ArrayCollection
     */
    public function getRegNumber()
    {
        return $this->regNumber;
    }

    /**
     * Añadir numero de registro
     * @param User $regNumber
     * @return Company
     */
    public function addRegNumber(User $regNumber): self
    {
        if (!$this->regNumber->contains($regNumber)) {
            $this->regNumber[] = $regNumber;
            $regNumber->setCompany($this);
        }
        return $this;
    }

    /**
     * Borrar numero de registro
     * @param User $regNumber
     * @return Company
     */
    public function removeRegNumber(User $regNumber): self
    {
        if ($this->regNumber->contains($regNumber)) {
            $this->regNumber->removeElement($regNumber);
            // set the owning side to null (unless already changed)
            if ($regNumber->getCompany() === $this) {
                $regNumber->setCompany(null);
            }
        }
        return $this;
    }
}
