<?php

namespace App\Core\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Base fixture of the Core module.
 */
abstract class AbstractFixture extends Fixture implements FixtureGroupInterface
{
    /** Groups for this fixture */
    protected const FIXTURE_GROUPS = [
        'core',
    ];


    /**
     * {@inheritDoc}
     */
    abstract public function load(ObjectManager $manager): void;


    /**
     * {@inheritDoc}
     */
    public static function getGroups(): array
    {
        return static::FIXTURE_GROUPS;
    }
}
