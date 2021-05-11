<?php
/**
 * Entidad Estado de la visita
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Estado de la visita
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /** Pending status ID (@var int) */
    public const PENDING = 1;

    /** Done status ID (@var int) */
    public const DONE = 2;

    /** Canceled status ID (@var int) */
    public const CANCELED = 3;


    /**
     * identificador del estado
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre del estado
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * Numero de registro
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="status")
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
     * status to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * obtiene el identificador del estado
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre del estado
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * establece el nombre del estado
     * @param string $name
     * @return Status
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
     * @return Status
     */
    public function addVisit(Visit $visit): self
    {
        if (!$this->visit->contains($visit)) {
            $this->visit[] = $visit;
            $visit->setStatus($this);
        }

        return $this;
    }

    /**
     * Borrar visita
     * @param Visit $visit
     *
     * @return Status
     */
    public function removeVisit(Visit $visit): self
    {
        if ($this->visit->contains($visit)) {
            $this->visit->removeElement($visit);
            if ($visit->getStatus() === $this) {
                $visit->setStatus(null);
            }
        }

        return $this;
    }
}
