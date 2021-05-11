<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialCotizacionFavoritosRepository")
 */
class ComercialCotizacionFavoritos
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $cotizacion;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cotizacionesFavoritas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \Datetime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCotizacion(): ?string
    {
        return $this->cotizacion;
    }

    /**
     * @param string|null $cotizacion
     *
     * @return ComercialCotizacionFavoritos
     */
    public function setCotizacion(?string $cotizacion): self
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return ComercialCotizacionFavoritos
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return ComercialCotizacionFavoritos
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
