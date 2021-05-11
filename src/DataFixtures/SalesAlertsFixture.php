<?php

namespace App\DataFixtures;

use App\Entity\ComercialAlertas;
use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\User;
use App\Entity\Vertical;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SalesAlertsFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 50;
    private const MAX_USERS          = 2;
    private const MAX_RECORDS        = 100;
    private const MAX_QUOTES         = 50;
    private const MAX_VERTICAL       = 7;
    private const MAX_OFFICES        = 6287;
    private const MAX_CLIENTS        = 90;
    private const MAX_PROVIDERS      = 100;

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
            SalesQuoteFixture::class,
            VerticalFixture::class,
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
            ComercialAlertas::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialAlertas $content, $count) use ($class, $manager): void {
                $content
                    ->setMissatge($class->faker->text())
                    ->setNivel($class->faker->numberBetween(1, 10))
                    ->setHorizontal(null)
                    ->setCreatedAt($createdAt = $class->faker->dateTimeBetween('now', '+6 months'))
                    ->setUpdatedAt($updatedAt = $class->faker->dateTimeInInterval($createdAt->format('Y-m-d H:i:s'), '+ 1 day'))
                ;

                if ($class->faker->boolean(10)) {
                    $content->setActive(0);
                }
                if ($class->faker->boolean(20)) {
                    $content->setCotizacion((string) $class->getReference(ComercialCotizacion::class.'_'.random_int(1, self::MAX_QUOTES))->getNumCoti());
                }
                if ($class->faker->boolean(20)) {
                    $content->setExpediente($class->getReference(ComercialExpediente::class.'_'.random_int(1, self::MAX_RECORDS)));
                }
                if ($class->faker->boolean(20)) {
                    $content->setVertical($class->getReference(Vertical::class.'_'.random_int(1, self::MAX_VERTICAL)));
                }
                if ($class->faker->boolean(20)) {
                    $content->setPersonaNif(self::getRandClientNIF($manager));
                }
                if ($class->faker->boolean(20)) {
                    $content->setPersonaNif(self::getRandPrescriberCIF($manager));
                }
                if ($class->faker->boolean(40)) {
                    $content->setOficina(self::getRandOffice($manager));
                }
                if ($class->faker->boolean()) {
                    $content
                        ->setIsAlert(0);
                } else {
                    $content
                        ->setIsAlert(1);
                }
                if ($class->faker->boolean(90)) {
                    $content
                        ->setActive(1)
                        ->setDeleted(0)
                    ;

                    return;
                }
                $content
                    ->setActive(0)
                    ->setDeleted(1)
                    ->setDeletedBy($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setDeletedAt($class->faker->dateTimeBetween('now', '+6 months'));
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
    private function getRandOffice(ObjectManager $manager): string
    {
        return $manager->find(MasterOffice::class, (int) random_int(1, self::MAX_OFFICES))->getCodigo();
    }

    /**
     * @param ObjectManager $manager
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getRandClientNIF(ObjectManager $manager): string
    {
        return $manager->find(MasterCustomer::class, (int) random_int(1, self::MAX_CLIENTS))->getNif();
    }

    /**
     * @param ObjectManager $manager
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getRandPrescriberCIF(ObjectManager $manager): string
    {
        return $manager->find(MasterProvider::class, (int) random_int(1, self::MAX_PROVIDERS))->getNif();
    }
}
