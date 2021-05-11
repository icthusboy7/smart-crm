<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialExpedientePerdidoMotivoRepository")
 */
class ComercialExpedientePerdidoMotivo
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
     * @ORM\Column(type="string", length=255)
     */
    private $motivo;

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
    public function getMotivo(): ?string
    {
        return $this->motivo;
    }

    /**
     * @param string $motivo
     *
     * @return ComercialExpedientePerdidoMotivo
     */
    public function setMotivo(string $motivo): self
    {
        $this->motivo = $motivo;

        return $this;
    }
}
