<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialExpedienteFavoritosRepository")
 */
class ComercialExpedienteFavoritos
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
     * @var ComercialExpediente
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialExpediente", inversedBy="usuariosExpedienteFavorito", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediente;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="expedientesFavoritos", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
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
     * @return ComercialExpediente|null
     */
    public function getExpediente(): ?ComercialExpediente
    {
        return $this->expediente;
    }

    /**
     * @param ComercialExpediente|null $expediente
     *
     * @return ComercialExpedienteFavoritos
     */
    public function setExpediente(?ComercialExpediente $expediente): self
    {
        $this->expediente = $expediente;

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
     * @return ComercialExpedienteFavoritos
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
     * @return ComercialExpedienteFavoritos
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
