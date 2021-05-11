<?php

namespace App\Core\Entity;

use App\Entity\Concerns\IsEntity;
use App\Entity\Traits\HasTimestampsTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base superclass for entities.
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractEntity implements IsEntity
{
    use HasTimestampsTrait;

    /**
     * Entity identifier.
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Obtains this entity's identifier.
     *
     * @return int      ID of the entity
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Compares two entites for equality.
     *
     * Two entities are considered equal if they are instances of the
     * same class and their identifiers have the same value.
     *
     * @param object $object Object to which to compare
     *
     * @return               True if they are equivalent
     */
    public function equals(object $object): bool
    {
        if (is_a($object, static::class) === false) {
            return false;
        }

        return $object->getId() === $this->getId();
    }


    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $id = $this->getId();
        $class = static::class;

        return "${class}(${id})";
    }
}
