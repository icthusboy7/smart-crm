<?php
/**
 * Entidad GestorResponsable - Tabla relación
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class gestor responsable
 * @ORM\Entity(repositoryClass="App\Repository\GestorResponsableRepository")
 */
class GestorResponsable
{
    /**
     * Identificador gestor
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $Gestor;

    /**
     * Identificador responsable
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, nullable=false)
     */
    private $Responsable;

    /**
     * Obtener gestor
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
