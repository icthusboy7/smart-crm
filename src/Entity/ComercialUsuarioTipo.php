<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comercial_usuario_tipo")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialUsuarioTipoRepository")
 */
class ComercialUsuarioTipo
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, unique=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=255)
     */
    private $grupo;

    /**
     * Relación entre ComercialUsuarioTipo y ComercialUsuario
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialUsuario", mappedBy="tipo")
     */
    private $comercialusuariotipo;

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
     * @return string
     */
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getGrupo(): string
    {
        return $this->grupo;
    }

    /**
     * @param string $grupo
     */
    public function setGrupo(string $grupo): void
    {
        $this->grupo = $grupo;
    }
}
