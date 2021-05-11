<?php
/**
 * Entidad Horizontal
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Clase Horizontal
 * @ORM\Entity(repositoryClass="App\Repository\HorizontalRepository")
 */
class Horizontal
{
    /**
     * Identificador de Horizontal
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre de Horizontal
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * Retorna el nombre de Horizontal
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }

    /**
     * obtiene el id de Horizontal
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre de Horizontal
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * establece el nombre de Horizontal
     * @param string $name
     * @return Horizontal
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
