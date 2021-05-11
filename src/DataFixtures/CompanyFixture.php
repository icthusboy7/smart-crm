<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CompanyFixture
 */
class CompanyFixture extends Fixture implements FixtureGroupInterface
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
            'CEF' => 'CEF',
            'CCF' => 'CCF',
            'CBK' => 'CBK',
        ];
        $count   = 0;
        foreach ($content as $key => $value) {
            $count++;
            $content = new Company();
            $content->setCompanyName($key);
            $content->setCompanyShort($value);
            $manager->persist($content);

            // Add references to create content related with
            $this->addReference(Company::class.'_'.$count, $content);
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
            'test',
        ];
    }
}
