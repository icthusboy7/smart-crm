<?php

namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * This trait defines columns for an entity creation and last update
 * dates and implements the interface {@code HasTimestamps} to provide
 * access to them. Notice that those fields are intended to be updated
 * automatically by an entity listener.
 *
 * @see App\Entity\Concerns\HasTimestamps
 */
trait HasTimestampsTrait
{
    /**
     * This entity's creation date.
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * This entity's last update date.
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;


    /**
     * Obtains this entity's creation date attribute.
     *
     * @return DateTime This entity's creation date
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * Sets this entity's creation date attribute.
     *
     * @param DateTime $date New date value
     *
     * @return This entity
     */
    public function setCreatedAt(DateTimeInterface $date): self
    {
        $this->createdAt = $date;

        return $this;
    }


    /**
     * Obtains this entity's update date attribute.
     *
     * @return DateTime This entity's last update date
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }


    /**
     * Sets this entity's creation date attribute.
     *
     * @param DateTime $date New date value
     *
     * @return This entity
     */
    public function setUpdatedAt(DateTimeInterface $date): self
    {
        $this->updatedAt = $date;

        return $this;
    }
}
