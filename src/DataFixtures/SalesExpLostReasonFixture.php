<?php

namespace App\DataFixtures;

use App\Entity\ComercialExpedientePerdidoMotivo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SalesExpLostReasonFixture
 */
class SalesExpLostReasonFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {

        /** @var array $content */
        $content = [
            'CaixaBank',
            'Precio (competencia)',
            'Denegada',
            'Desistida',
        ];
        foreach ($content as $key => $value) {
            $content = new ComercialExpedientePerdidoMotivo();
            $content->setMotivo($value);
            $manager->persist($content);
            $i = (int) $key + 1;
            $this->addReference(ComercialExpedientePerdidoMotivo::class.'_'.$i, $content);
        }

        $manager->flush();
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
            'pre',
            'test',
        ];
    }
}
