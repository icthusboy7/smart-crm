<?php
/**
 * Entidad Estado de la cotización
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Estado de la cotización
 * @ORM\Entity(repositoryClass="App\Repository\StatusQuotationRepository")
 */
class StatusQuotation
{
    /**
     * Identificador del estado de la cotización
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * nombre del estado de la cotización
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * obtiene el id del estado de la cotización
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene el nombre
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Establece el nombre
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
