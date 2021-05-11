<?php

namespace App\Entity\Concerns;

/**
 * An entity that has an identifier and timestamps.
 */
interface IsEntity extends HasTimestamps
{
    /**
     * Obtains this entity's identifier.
     *
     * @return int      ID of the entity
     */
    public function getId(): int;


    /**
     * Compares two entities for equality.
     *
     * @param object $object Object to which to compare
     *
     * @return               True if they are equivalent
     */
    public function equals(object $object): bool;
}
