<?php
/**
 * Entidad CommercialResponsable
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CommercialResponsable
 * @ORM\Entity(repositoryClass="App\Repository\CommercialResponsableRepository")
 */
class CommercialResponsable
{
    /**
     * ID comercial
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, unique=true, nullable=false)
     */
    private $Commercial;

    /**
     * ID responsable
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $Responsable;

    /**
     * Obtemer comercial
     * @return mixed
     */
    public function getCommercial()
    {
        return $this->Commercial;
    }

    /**
     * Establecer comercial
     * @param mixed $Commercial
     */
    public function setCommercial($Commercial): void
    {
        $this->Commercial = $Commercial;
    }

    /**
     * Obtener responsable
     * @return mixed
     */
    public function getResponsable()
    {
        return $this->Responsable;
    }

    /**
     * Establecer responsable
     * @param mixed $Responsable
     */
    public function setResponsable($Responsable): void
    {
        $this->Responsable = $Responsable;
    }
}
