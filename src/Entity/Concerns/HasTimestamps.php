<?php

namespace App\Entity\Concerns;

use DateTimeInterface;

/**
 * An entity that implement this interface contains time stamps of
 * the entity's creation and its last update dates.
 */
interface HasTimestamps
{
    /**
     * Obtains this entity's creation date attribute.
     *
     * @return DateTime This entity's creation date
     */
    public function getCreatedAt(): ?DateTimeInterface;


    /**
     * Sets this entity's creation date attribute.
     *
     * @param DateTime $date New date value
     */
    public function setCreatedAt(DateTimeInterface $date);


    /**
     * Obtains this entity's update date attribute.
     *
     * @return DateTime This entity's last update date
     */
    public function getUpdatedAt(): ?DateTimeInterface;


    /**
     * Sets this entity's creation date attribute.
     *
     * @param DateTime $date New date value
     */
    public function setUpdatedAt(DateTimeInterface $date);
}
