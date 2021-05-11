<?php
/**
 * Entidad Notifications
 */
namespace App\Entity;

use App\Entity\Concerns\HasTimestamps;
use App\Entity\Traits\HasTimestampsTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clase Notifications
 * @ORM\Entity(repositoryClass="App\Repository\NotificationsRepository")
 */
class Notifications implements HasTimestamps
{
    use HasTimestampsTrait;

    /**
     * Identificador de Notifications
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * descripción de Notifications
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     */
    private $url;
    /**
     * obtiene el id de Notifications
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * obtiene la descripción de Notifications
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * establece la descripción de Notifications
     *
     * @param string|null $description
     *
     * @return Notifications
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
