<?php

namespace App\DataFixtures;

use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteFavoritos;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SalesRecordFavFixture
 */
class SalesRecordFavFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 30;
    private const MAX_RECORDS        = 100;
    private const MAX_USERS          = 2;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            SalesRecordFixture::class,
            UserFixture::class,
        ];
    }

    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return array
     */
    public static function getGroups(): array
    {
        return [
            'dev',
            'test',
        ];
    }
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    protected function loadData(ObjectManager $manager): void
    {
        $class = $this;
        $this->createMany(
            ComercialExpedienteFavoritos::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialExpedienteFavoritos $content, $count) use ($class, $manager): void {
                $content
                    ->setCreatedAt($class->faker->dateTimeBetween('now', '+6 months'))
                    ->setUser($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setExpediente($class->getReference(ComercialExpediente::class.'_'.random_int(1, self::MAX_RECORDS)))
                ;
            }
        );
        $manager->flush();
    }
}
