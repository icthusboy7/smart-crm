<?php
/**
 * Entidad Type
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Type
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * Identificador del tipo
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre del tipo
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * obtiene el identificador del tipo
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre del tipo
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * establece el nombre del tipo
     * @param string $name
     * @return Type
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
