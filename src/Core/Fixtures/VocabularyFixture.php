<?php

namespace App\Core\Fixtures;

use App\Core\Entity\Vocabs;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixture of the controlled vocabularies of the Core module.
 */
class VocabularyFixture extends AbstractFixture
{
    /**
     * Vocabularies this fixture must load
     */
    private const VOCABULARIES = [
        Vocabs\ContactKind::class,
    ];


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager): void
    {
        foreach (static::VOCABULARIES as $class) {
            foreach ($class::entities() as $entity) {
                $manager->merge($entity);
            }
        }

        $manager->flush();
    }
}
