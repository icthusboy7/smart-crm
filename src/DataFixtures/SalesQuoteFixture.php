<?php

namespace App\DataFixtures;

use App\Entity\ComercialCotizacion;
use App\Entity\MasterQuotation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

/**
 * Class SalesQuoteFixture
 */
class SalesQuoteFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 50;
    private const MAX_USERS          = 2;
    private const MAX_QUOTES         = 500;
    private const MAX_TASKS          = 40;
    private const DEF_TERM           = [ 12, 24, 36, 48, 60, 72, 84, 96, 108, 120 ];
    private const DEF_STATUS         = [
        'Denegada',
        'Vigente y archivada',
        'Pendiente solicitud',
        'Pendiente formalizar',
        'Vigente',
        'Desistida',
        '',
        'Pendiente aprob riesgo cliente',
        'Archivable',
        'Pendiente activación y archivo',
        'Vencido',
    ];

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
            SalesTaskFixture::class,
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
            ComercialCotizacion::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialCotizacion $content, $count) use ($class, $manager): void {
                $randCoti = self::getRandQuoteCode($manager, $class);

                $content
                    ->setAutor($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setCreatedAt($createdAt = $class->faker->dateTimeBetween('now', '+6 months'))
                    ->setUpdatedAt($updatedAt = $class->faker->dateTimeInInterval($createdAt->format('Y-m-d H:i:s'), '+ 1 day'))
                    ->setFechaEstado($updatedAt)
                    ->setEstado($class->faker->randomElement(self::DEF_STATUS))
                    ->setChecklist([])
                    ->setFechaTarea($updatedAt->format('Y-m-d'))
                    ->setIdTarea($class->faker->numberBetween(1, self::MAX_TASKS))
                    ->setIndicaciones($updatedAt->format('Y-m-d').': '.$class->faker->text())
                    ->setCuota($cuota = $class->faker->randomFloat(2, 1000, 50000))
                    ->setNumCoti($randCoti['numCoti'])
                    ->setSolicitanteNif(($randCoti['nifPr']) ?? $randCoti['nifCl'])
                    ->setSolicitanteNombre($randCoti['nombre'] ?? $class->faker->words())
                    ->setPlazo($plazo = $class->faker->randomElement(self::DEF_TERM))
                    ->setInversion($cuota * $plazo)
                ;
            }
        );

        $manager->flush();
    }

    /**
     * @param ObjectManager     $manager
     * @param SalesQuoteFixture $class
     *
     * @return array
     *
     * @throws Exception
     */
    private function getRandQuoteCode(ObjectManager $manager, SalesQuoteFixture $class): array
    {
        $randItem = $class->faker->unique()->numberBetween(1, self::MAX_QUOTES);
        $coti     = $manager->find(MasterQuotation::class, $randItem);

        return [
            'numCoti' => $coti->getCodigoCoti(),
            'nifPr'   => $coti->getNifPrescriptor(),
            'nifCl'   => $coti->getNifCliente(),
            'nombre'  => $coti->getNombreCliente(),
        ];
    }
}
