<?php

namespace App\Core\Entity;

use App\Entity\Concerns\IsEntity;
use App\Entity\Concerns\IsTerm;
use App\Entity\Traits\HasTimestampsTrait;
use BadMethodCallException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;

/**
 * Base superclass for all the vocabularies.
 *
 * A vocabulary entity is an entity with an identifier and a name that
 * pertains to a controlled vocabulary. To define a vocabulary for the
 * type, constants must be added to the class with the expected identifier
 * and name of the vocabulary terms.
 *
 * For example:
 *
 * <code>
 * class Status extends AbstractVocabulary {
 *     private const DONE =    [1, 'Done'];
 *     private const PENDING = [2, 'Pending'];
 * }
 * </code>
 *
 * To access the vocabulary terms:
 *
 * <code>
 * $status = Status::PENDING();
 * </code>
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractVocabulary implements IsEntity, IsTerm
{
    use HasTimestampsTrait;

    /**
     * Vocabulary cache.
     * @var array
     */
    private static $cache = [];


    /**
     * Entity identifier.
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Entity name.
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;


    /**
     * Constructs a new vocabulary term.
     *
     * @param int    $id   Unique identifier
     * @param string $name Unique name
     */
    protected function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }


    /**
     * Obtains this entity identifier.
     *
     * @return int      ID of the entity
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Obtains this entity name.
     *
     * @return string   Name of the entity
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

        return $object->getId() === $this->getId();
    }


    /**
     * Obtains a reference to this term using the given object
     * manager. This is useful to set a value for a relation without
     * hitting the database since we already know the identifier.
     *
     * @param ObjectManager $manager Object manager
     *
     * @return An entity reference
     */
    public function reference(ObjectManager $manager): object
    {
        return $manager->getReference(static::class, $this->getId());
    }


    /**
     * Obtains a view of the entities defined on the vocabulary.
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
                "The vocabulary does not define a '${key}' entity"
            );
        }

        return $entities[$key];
    }


    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $id = $this->getId();
        $name = addslashes($this->getName());
        $class = static::class;

        return "${class}(${id}, \"${name}\")";
    }


    /**
     * Obtain the entity linked to the given identifier.
     *
     * @param int $id Entity identifier
     *
     * @return        Entity value
     *
     * @throws InvalidArgumentException
     */
    public static function fromValue(int $id): object
    {
        foreach (static::entities() as $entity) {
            if ($id === $entity->getId()) {
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
        $cache = &AbstractVocabulary::$cache;

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
        $cache = &AbstractVocabulary::$cache;

        $cache[$class] = [];

        foreach ($refs->getConstants() as $key => $value) {
            $instance = new $class(...$value);
            $cache[$class][$key] = $instance;
        }
    }
}
