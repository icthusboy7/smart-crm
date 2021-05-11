<?php

namespace App\Entity\Concerns;

/**
 * An entity that is part of a controlled vocabulary. That is, it
 * is part of a constant predefined set of values.
 */
interface IsTerm
{
    /**
     * Obtains this entity name.
     *
     * @return string   Name of the entity
     */
    public function getName(): string;
}
