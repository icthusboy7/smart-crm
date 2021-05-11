<?php

namespace App\Entity;

use \DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="comercial_task_type")
 * @ORM\Entity(repositoryClass="App\Repository\ComercialTaskTypeRepository")
 */
class ComercialTaskType
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
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isSpecial;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $form;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;


    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;


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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return ComercialTaskType
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->description;
    }

    /**
     * @return bool|null
     */
    public function getIsSpecial(): ?bool
    {
        return $this->isSpecial;
    }

    /**
     * @param bool $isSpecial
     *
     * @return ComercialTaskType
     */
    public function setIsSpecial(bool $isSpecial): self
    {
        $this->isSpecial = $isSpecial;

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
     * @return ComercialTaskType
     */
    public function setForm(?string $form): self
    {
        $this->form = $form;

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
     * @return ComercialTaskType
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}
