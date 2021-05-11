<?php

namespace App\DataFixtures;

use App\Entity\Charge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ChargeFixture
 */
class ChargeFixture extends Fixture implements FixtureGroupInterface
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
            'Director/a Financiero',
            'Director/a Compras',
            'Director/a Comercial',
            'Comercial',
            'Gerente',
            'Otro',
        ];
        foreach ($content as $key => $value) {
            $content = new Charge();
            $content->setName($value);
            $manager->persist($content);
            $i = (int) $key + 1;
            $this->addReference(Charge::class.'_'.$i, $content);
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
        ];
    }
}
