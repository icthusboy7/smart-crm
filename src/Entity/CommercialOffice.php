<?php
/**
 * Entidad CommercialOffice
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CommercialOffice
 * @ORM\Entity(repositoryClass="App\Repository\CommercialOfficeRepository")
 */
class CommercialOffice
{
    /**
     * Id de oficina
     * @ORM\Id()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $Office;

    /**
     * Id de comercial
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $Commercial;

    /**
     * Id de horizontal
     * @ORM\Id()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $Horizontal;

    /**
     * @return mixed
     */
    public function getOffice()
    {
        return $this->Office;
    }

    /**
     * @param mixed $Office
     */
    public function setOffice($Office): void
    {
        $this->Office = $Office;
    }

    /**
     * Obtener id comercial
     * @return mixed
     */
    public function getCommercial()
    {
        return $this->Commercial;
    }

    /**
     * Establecer id comercial
     * @param mixed $Commercial
     */
    public function setCommercial($Commercial): void
    {
        $this->Commercial = $Commercial;
    }

    /**
     * Obtener Horizontal
     * @return mixed
     */
    public function getHorizontal()
    {
        return $this->Horizontal;
    }

    /**
     * Establecer Horizontal
     * @param mixed $Horizontal
     */
    public function setHorizontal($Horizontal): void
    {
        $this->Horizontal = $Horizontal;
    }
}
