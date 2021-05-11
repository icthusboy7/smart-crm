<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialUsuarioZonaRepository")
 */
class ComercialUsuarioZona
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
     * @ORM\Column(name="zona", type="string", length=255, unique=true)
     */
    private $zona;

    /**
     * @var User
     * Responsable
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="responsableZona")
     */
    private $responsable;

    /**
     * Relación entre ComercialUsuarioZona y ComercialUsuario
     * @var mixed
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComercialUsuario", mappedBy="equipo")
     */
    private $comercialusuariozona;

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
    public function getZona(): string
    {
        return $this->zona;
    }

    /**
     * @param string $zona
     */
    public function setZona(string $zona): void
    {
        $this->zona = $zona;
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
}
