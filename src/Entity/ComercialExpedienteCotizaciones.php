<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialExpedienteCotizacionesRepository")
 */
class ComercialExpedienteCotizaciones
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialExpediente", inversedBy="cotizaciones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $expedienteID;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $cotizacion;

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
     * @return ComercialExpediente|null
     */
    public function getExpedienteID(): ?ComercialExpediente
    {
        return $this->expedienteID;
    }

    /**
     * @param ComercialExpediente|null $expedienteID
     *
     * @return ComercialExpedienteCotizaciones
     */
    public function setExpedienteID(?ComercialExpediente $expedienteID): self
    {
        $this->expedienteID = $expedienteID;

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
     * @return ComercialExpedienteCotizaciones
     */
    public function setCotizacion(?string $cotizacion): self
    {
        $this->cotizacion = $cotizacion;

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
     * @return ComercialExpedienteCotizaciones
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
