<?php
/**
 * Entidad Vertical
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Vertical
 * @ORM\Entity(repositoryClass="App\Repository\VerticalRepository")
 */
class Vertical
{
    /**
     * identificador del vertical
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre del vertical
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * Numero de registro
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="vertical")
     */
    private $visit;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->visit = new ArrayCollection();
    }

    /**
     * Retorna el nombre de Vertical
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * obtiene el identificador
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre del vertical
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setea el nombre del vertical
     * @param string $name
     * @return Vertical
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
            $visit->setVertical($this);
        }

        return $this;
    }

    /**
     * Borrar visita
     * @param Visit $visit
     *
     * @return Vertical
     */
    public function removeVisit(Visit $visit): self
    {
        if ($this->visit->contains($visit)) {
            $this->visit->removeElement($visit);
            if ($visit->getVertical() === $this) {
                $visit->setVertical(null);
            }
        }

        return $this;
    }
}
