<?php

namespace App\DataFixtures;

use App\Entity\ComercialExpediente;
use App\Entity\ComercialMuro;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SalesWallFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 70;
    private const MAX_USERS          = 2;
    private const MAX_RECORD         = 100;
    private const MAX_TASK_TYPES     = 4;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            SalesRecordFixture::class,
            SalesTaskTypeFixture::class,
        ];
    }
    /**
     * This method must return an array of groups
     * on which the implementing class belongs to
     *
     * @return string[]
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
     * @return mixed|void
     */
    protected function loadData(ObjectManager $manager): void
    {
        $class = $this;
        $this->createMany(
            ComercialMuro::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialMuro $content, $count) use ($class, $manager): void {
                $content
                    ->setTipo(random_int(1, self::MAX_TASK_TYPES))
                    ->setAutor($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setGrupo(1)
                    ->setMissatge($class->faker->text(100))
                    ->setNivel(1)
                    ->setVisto($class->faker->optional(0.8, 0)->randomElement([0, 1]))
                ;
                if ($class->faker->optional(0.8)->randomDigit) {
                    $content->setExpediente($class->getReference(ComercialExpediente::class.'_'.random_int(1, self::MAX_RECORD)));
                }
                if ($class->faker->optional(0.3)->randomDigit) {
                    $content->setResponsable($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)));
                }
                if ($class->faker->optional(0.1)->randomDigit) {
                    $content->setCotizacion($class->faker->numberBetween(50325162, 50385162));
                }
                if ($class->faker->optional(0.1)->randomDigit) {
                    $content->setMotivoCanc($class->faker->randomElement(['Cancelada', 'No aceptada', 'Fuera de plazo']));
                }
                if ($class->faker->optional(0.1)->randomDigit) {
                    return;
                }
//                $content->setCerradoPor($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS))); ?? WTF
            }
        );

        $manager->flush();
    }
}
