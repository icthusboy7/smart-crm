<?php

namespace App\Entity;

use \DateTime;
use \Exception;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialMuroRepository")
 */
class ComercialMuro
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
     * @ORM\Column(type="text")
     */
    private $missatge;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $autor;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $responsable;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $visto;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $nivel;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $grupo;

    /**
     * @var ComercialExpediente
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialExpediente")
     * @ORM\JoinColumn(nullable=true)
     */
    private $expediente;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cotizacion;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    /**
     * @var ComercialMuro
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialMuro")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cerradoPor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motivoCanc;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tipo;

    /**
     * @var ComercialTask
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialTask", cascade={"persist", "remove"})
     */
    private $tareaPadre;

    /**
     * ComercialMuro constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }
    /**
     * get Id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getMissatge(): ?string
    {
        return $this->missatge;
    }

    /**
     * @param string $missatge
     *
     * @return ComercialMuro
     */
    public function setMissatge(string $missatge): self
    {
        $this->missatge = $missatge;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAutor(): ?User
    {
        return $this->autor;
    }

    /**
     * @param User|null $autor
     *
     * @return ComercialMuro
     */
    public function setAutor(?User $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    /**
     * @param User|null $responsable
     *
     * @return ComercialMuro
     */
    public function setResponsable(?User $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getVisto(): ?bool
    {
        return $this->visto;
    }

    /**
     * @param bool $visto
     *
     * @return ComercialMuro
     */
    public function setVisto(bool $visto): self
    {
        $this->visto = $visto;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNivel(): ?int
    {
        return $this->nivel;
    }

    /**
     * @param int $nivel
     *
     * @return ComercialMuro
     */
    public function setNivel(int $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGrupo(): ?int
    {
        return $this->grupo;
    }

    /**
     * @param int|null $grupo
     *
     * @return ComercialMuro
     */
    public function setGrupo(?int $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * @return ComercialExpediente|null
     */
    public function getExpediente(): ?ComercialExpediente
    {
        return $this->expediente;
    }

    /**
     * @param int|ComercialExpediente|null $expediente
     *
     * @return ComercialMuro
     */
    public function setExpediente(?ComercialExpediente $expediente): self
    {
        $this->expediente = $expediente;

        return $this;
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
     * @return ComercialMuro
     */
    public function setCotizacion(?string $cotizacion): self
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * @return ComercialMuro|null
     */
    public function getCerradoPor(): ?ComercialMuro
    {
        return $this->cerradoPor;
    }

    /**
     * @param ComercialMuro|null $cerradoPor
     *
     * @return ComercialMuro
     */
    public function setCerradoPor(?ComercialMuro $cerradoPor): self
    {
        $this->cerradoPor = $cerradoPor;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMotivoCanc(): ?string
    {
        return $this->motivoCanc;
    }

    /**
     * @param string|null $motivoCanc
     *
     * @return ComercialMuro
     */
    public function setMotivoCanc(?string $motivoCanc): self
    {
        $this->motivoCanc = $motivoCanc;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return ComercialMuro
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     *
     * @return ComercialMuro
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    /**
     * @param int|null $tipo
     *
     * @return ComercialMuro
     */
    public function setTipo(?int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return ComercialTask|null
     */
    public function getTareaPadre(): ?ComercialTask
    {
        return $this->tareaPadre;
    }

    /**
     * @param ComercialTask|null $tarea
     *
     * @return ComercialMuro
     */
    public function setTareaPadre(?ComercialTask $tarea): self
    {
        $this->tareaPadre = $tarea;

        return $this;
    }
}
