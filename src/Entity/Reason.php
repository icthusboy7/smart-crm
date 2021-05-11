<?php
/**
 * Entidad Motivos de la visita
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Motivos de la visita
 * @ORM\Entity(repositoryClass="App\Repository\ReasonRepository")
 */
class Reason
{
    /**
     * Identificador del motivo de la visita
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre del motivo de la visita
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Numero de registro
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="reason")
     */
    private $visit;

    /**
     * reason constructor.
     */
    public function __construct()
    {
        $this->visit = new ArrayCollection();
    }

    /**
     * reason to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * obtiene el identificador del motivo de la visita
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre del motivo de la visita
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * establece el nombre del motivo de la visita
     * @param string $name
     * @return Reason
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Obtener visita
     *
     * @return ArrayCollection
     */
    public function getVisit(): ArrayCollection
    {
        return $this->visit;
    }

    /**
     * Añadir visita
     * @param Visit $visit
     *
     * @return Reason
     */
    public function addVisit(Visit $visit): self
    {
        if (!$this->visit->contains($visit)) {
            $this->visit[] = $visit;
            $visit->setReason($this);
        }

        return $this;
    }

    /**
     * Borrar visita
     * @param Visit $visit
     *
     * @return Reason
     */
    public function removeVisit(Visit $visit): self
    {
        if ($this->visit->contains($visit)) {
            $this->visit->removeElement($visit);
            if ($visit->getReason() === $this) {
                $visit->setReason(null);
            }
        }

        return $this;
    }
}
