<?php

namespace App\DataFixtures;

use App\Entity\ComercialCotizacionFavoritos;
use App\Entity\MasterQuotation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SalesQuoteFavFixture
 */
class SalesQuoteFavFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 30;
    private const MAX_USERS          = 2;
    private const MAX_QUOTES         = 500;

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
            ComercialCotizacionFavoritos::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialCotizacionFavoritos $content, $count) use ($class, $manager): void {
                $content
                    ->setCreatedAt($class->faker->dateTimeBetween('now', '+6 months'))
                    ->setUser($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setCotizacion(self::getRandQuoteCode($manager))
                ;
            }
        );
        $manager->flush();
    }
    /**
     * @param ObjectManager $manager
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getRandQuoteCode(ObjectManager $manager): string
    {
        return $manager->find(MasterQuotation::class, (int) random_int(1, self::MAX_QUOTES))->getCodigoCoti();
    }
}
