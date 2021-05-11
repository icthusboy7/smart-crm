<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComercialExpedienteStatusRepository")
 */
class ComercialExpedienteStatus
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
    private $status;

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return $this->getStatus();
    }

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
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return ComercialExpedienteStatus
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
