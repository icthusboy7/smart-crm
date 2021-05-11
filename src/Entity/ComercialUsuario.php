<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comercial_usuario")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialUsuarioRepository")
 */
class ComercialUsuario
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * Responsable
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comercialusuario")
     */
    private $usuario;

    /**
     * @var ComercialUsuarioTipo
     * Responsable
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialUsuarioTipo", inversedBy="comercialusuariotipo")
     */
    private $tipo;

    /**
     * @var User
     * Responsable
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comercialusuarioresponsable")
     */
    private $responsable;

    /**
     * @var ComercialUsuarioZona
     * Responsable
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialUsuarioZona", inversedBy="comercialusuariotipo")
     */
    private $equipo;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUsuario(): User
    {
        return $this->usuario;
    }

    /**
     * @param User $usuario
     */
    public function setUsuario(User $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return ComercialUsuarioTipo
     */
    public function getTipo(): ComercialUsuarioTipo
    {
        return $this->tipo;
    }

    /**
     * @param ComercialUsuarioTipo $tipo
     */
    public function setTipo(ComercialUsuarioTipo $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return User
     */
    public function getResponsable(): User
    {
        return $this->responsable;
    }

    /**
     * @param User $responsable
     */
    public function setResponsable(User $responsable): void
    {
        $this->responsable = $responsable;
    }

    /**
     * @return ComercialUsuarioZona
     */
    public function getEquipo(): ComercialUsuarioZona
    {
        return $this->equipo;
    }

    /**
     * @param ComercialUsuarioZona $equipo
     */
    public function setEquipo(ComercialUsuarioZona $equipo): void
    {
        $this->equipo = $equipo;
    }
}
