<?php

namespace App\Core\Fixtures;

use Faker\Factory;
use Faker\Provider\es_ES\Person;
use Faker\Provider\es_ES\PhoneNumber;

use App\Core\Entity\Contact;
use App\Core\Entity\Vocabs\ContactKind;
use App\Core\Entity\Vocabs\ContactStatus;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixture for the contacts table.
 */
class ContactFixture extends AbstractFixture implements DependentFixtureInterface
{
    /** Number of entities to create */
    protected const NUM_ELEMENTS_ADDED = 500;

    /** Groups for this fixture */
    protected const FIXTURE_GROUPS = [
        'test',
    ];

    /** Dependencies for this fixture */
    protected const DEPENDENCIES = [
        VocabularyFixture::class,
    ];


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('es_ES');
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new PhoneNumber($faker));

        $states = ContactStatus::entities();
        $kinds = ContactKind::entities();

        foreach (range(1, static::NUM_ELEMENTS_ADDED) as $i) {
            $entity = new Contact();

            $status = $faker->randomElement($states);
            $kind = $faker->randomElement($kinds);

            $entity->setKind($kind->reference($manager));
            $entity->setName($faker->name);
            $entity->setNif($faker->unique()->dni);
            $entity->setStatus($status);

            if ($kind->equals(ContactKind::TEMPORARY())) {
                $status = ContactStatus::ACTIVE();
                $entity->setStatus($status);
            }

            if ($status->equals(ContactStatus::ACTIVE())) {
                $entity->setAddress($faker->address);
                $entity->setEmail($faker->email);
                $entity->setNotes($faker->text(50));
                $entity->setPhone($faker->phoneNumber);
            }

            $manager->persist($entity);
        }

        $manager->flush();
    }


    /**
     * {@inheritDoc}
     */
    public function getDependencies(): array
    {
        return static::DEPENDENCIES;
    }
}
