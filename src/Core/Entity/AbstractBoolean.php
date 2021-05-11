<?php

namespace App\Core\Entity;

use App\Entity\Concerns\IsTerm;
use BadMethodCallException;
use ReflectionClass;

/**
 * Base superclass for boolean vocabularies. Noticie that this entity
 * is unmapped, an thus the vocabulary values are not stored on a
 * database table.
 *
 * Example usage:
 *
 * <code>
 * class Status extends AbstractBoolean {
 *     private const DONE =    [true, 'Done'];
 *     private const PENDING = [false, 'Pending'];
 * }
 * </code>
 *
 * To access the vocabulary terms:
 *
 * <code>
 * $status = Status::PENDING();
 * </code>
 */
abstract class AbstractBoolean implements IsTerm
{
    /**
     * Vocabulary cache.
     * @var array
     */
    private static $cache = [];


    /**
     * Term value.
     * @var bool
     */
    private $value;


    /**
     * Term name.
     * @var string
     */
    private $name;


    /**
     * Constructs a new vocabulary term.
     *
     * @param int    $value Boolean value
     * @param string $name  Unique name
     */
    private function __construct(bool $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }


    /**
     * Obtains this term value.
     *
     * @return bool Value of the term
     */
    public function getValue(): bool
    {
        return $this->value;
    }


    /**
     * Obtains this term name.
     *
     * @return string   Name of the term
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Compares two vocabulary terms for equality.
     *
     * Two term are considered equal if they are instances of the same
     * class and their identifiers have the same value.
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

        return $object->getValue() === $this->getValue();
    }


    /**
     * Obtains a view of the defined vocabulary.
     *
     * @return Vocabulary entities
     */
    public static function entities(): array
    {
        return static::entitiesFor(static::class);
    }


    /**
     * {@inheritDoc}
     */
    public static function __callStatic($key, $arguments): object
    {
        $entities = static::entitiesFor(static::class);

        if (!key_exists($key, $entities)) {
            throw new BadMethodCallException(
                "The vocabulary does not define a '${key}' term"
            );
        }

        return $entities[$key];
    }


    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $id = $this->getValue() ? 'true' : 'false';
        $name = addslashes($this->getName());
        $class = static::class;

        return "${class}(${id}, \"${name}\")";
    }


    /**
     * Obtain the entity linked to the given boolean value.
     *
     * @param bool $value Boolean value
     *
     * @return            Entity value
     *
     * @throws InvalidArgumentException
     */
    public static function fromValue(bool $value): object
    {
        foreach (static::entities() as $entity) {
            if ($value === $entity->getValue()) {
                return $entity;
            }
        }

        throw new InvalidArgumentException();
    }


    /**
     * Obtains the entities for the given class. This method creates
     * and caches the vocabulary entities when required.
     *
     * @param string $class Vocabulary class name
     *
     * @return              Vocabulary class entities
     */
    private static function entitiesFor(string $class): array
    {
        $cache = &AbstractBoolean::$cache;

        if (!key_exists($class, $cache)) {
            static::initialize($class);
        }

        return $cache[$class];
    }


    /**
     * Initialize the entities for the given vocabulary. This creates
     * array of new instances of the defined vocabulary terms and
     * stores them on the cache.
     *
     * @param string $class Class name
     */
    private static function initialize(string $class): void
    {
        $refs = new ReflectionClass($class);
        $cache = &AbstractBoolean::$cache;

        $cache[$class] = [];

        foreach ($refs->getConstants() as $key => $value) {
            $instance = new $class($value[0], $value[1]);
            $cache[$class][$key] = $instance;
        }
    }
}
