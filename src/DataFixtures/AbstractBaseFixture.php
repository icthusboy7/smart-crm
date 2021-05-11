<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\es_ES\Person;
use Faker\Provider\es_ES\PhoneNumber;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AbstractClass BaseFixture
 */
abstract class AbstractBaseFixture extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker   = Factory::create('es_ES');
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->loadData($manager);
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(?ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
    /**
     * @param ObjectManager $manager
     *
     * @return mixed
     */
    abstract protected function loadData(ObjectManager $manager);


    /**
     * @param string   $className
     * @param int      $count
     * @param callable $factory
     */
    protected function createMany(string $className, int $count, callable $factory): void
    {
        $count++;
        for ($i = 1; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className.'_'.$i, $entity);
        }
    }


    /**
     * Obtains the stored references for the given class.
     *
     * Useful for obtaining random elements from the database without
     * knowing their identifiers in advance. Notice that the references
     * are obtained from the object manager by ID to prevent Doctrine
     * from complaining about non-persisted entities.
     *
     * @param string $className An entity class name
     *
     * @return                  Entity references array
     */
    protected function getReferences(string $className): array
    {
        $references = [];
        $refs = $this->referenceRepository;

        foreach ($refs->getReferences() as $key => $value) {
            if (is_a($value, $className)) {
                $id = $value->getId();
                $entity = $this->manager->find($className, $id);
                $references[] = $entity;
            }
        }

        return $references;
    }
}
