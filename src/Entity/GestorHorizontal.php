<?php
/**
 * Entidad GestorHorizontal - Tabla de relación
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GestorHorizontal
 * @ORM\Entity(repositoryClass="App\Repository\GestorHorizontalRepository")
 */
class GestorHorizontal
{

    /**
     * Identificador de gestor
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $Gestor;

    /**
     * Identificador de horizontal
     * @ORM\Id()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $Horizontal;

    /**
     * Obtener Gestor
     * @return mixed
     */
    public function getGestor()
    {
        return $this->Gestor;
    }

    /**
     * Establecer gestor
     * @param mixed $Gestor
     */
    public function setGestor($Gestor): void
    {
        $this->Gestor = $Gestor;
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
