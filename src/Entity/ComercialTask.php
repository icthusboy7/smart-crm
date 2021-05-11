<?php

namespace App\Entity;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comercial_task")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialTaskRepository")
 */
class ComercialTask
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var User $responsible
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $responsible;

    /**
     * @var ComercialTaskStatus $status
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialTaskStatus")
     */
    private $status;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $seen;

    /**
     * @var ComercialTaskType $type
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComercialTaskType")
     */
    private $type;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime $updatedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt = null;

    /**
     * @var DateTime $deletedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt = null;

    /**
     * @var User $createdBy
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $createdBy;

    /**
     * @var User $deletedBy
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $deletedBy;

    /**
     * @var ComercialMuro $comercialMuro
     *
     * @ORM\OneToOne(targetEntity="App\Entity\ComercialMuro")
     */
    private $comercialMuro;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $form;

    /**
     * ComercialTask constructor.
     */
    public function __construct()
    {
        $this->status    = new ComercialTaskStatus();
        $this->seen      = false;
        $this->createdAt = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getResponsible(): ?User
    {
        return $this->responsible;
    }

    /**
     * Establecer responsable.
     * @param User|null $responsible
     *
     * @return ComercialTask
     */
    public function setResponsible(?User $responsible = null): self
    {
        $this->responsible = $responsible;

        return $this;
    }

    /**
     * @return ComercialTaskStatus|null
     */
    public function getStatus(): ?ComercialTaskStatus
    {
        return $this->status;
    }

    /**
     * @param ComercialTaskStatus $status
     *
     * @return ComercialTask
     */
    public function setStatus(ComercialTaskStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return ComercialTask
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSeen(): bool
    {
        return $this->seen;
    }

    /**
     * @param bool $seen
     *
     * @return ComercialTask
     */
    public function setSeen(bool $seen): self
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Obtener Tipo
     * @return ComercialTaskType|null
     */
    public function getType(): ?ComercialTaskType
    {
        return $this->type;
    }

    /**
     * Establecer Tipo
     * @param ComercialTaskType|null $type
     *
     * @return self|null
     */
    public function setType(?ComercialTaskType $type): ?self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     *
     * @return ComercialTask
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
     * @param DateTime|null $updatedAt
     *
     * @return ComercialTask
     */
    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime|null $deletedAt
     *
     * @return ComercialTask
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return ComercialTask
     */
    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    /**
     * @param User|null $deletedBy
     *
     * @return ComercialTask
     */
    public function setDeletedBy(?User $deletedBy = null): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * @return ComercialMuro|null
     */
    public function getComercialMuro(): ?ComercialMuro
    {
        return $this->comercialMuro;
    }

    /**
     * @param ComercialMuro|null $comercialMuro
     *
     * @return ComercialTask
     */
    public function setComercialMuro(?ComercialMuro $comercialMuro): self
    {
        $this->comercialMuro = $comercialMuro;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getForm(): ?string
    {
        return $this->form;
    }

    /**
     * @param string|null $form
     *
     * @return ComercialTask
     */
    public function setForm(?string $form): self
    {
        $this->form = $form;

        return $this;
    }
}
