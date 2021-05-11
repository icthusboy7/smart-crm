<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class RoleFixture
 */
class RoleFixture extends Fixture implements FixtureGroupInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $count = 0;
        /** @var array $content */
        $content = [
            'ROLE_ADMIN',
            'ROLE_SISTEMAS_CEF_SEGURO_TIPO',
            'ROLE_SISTEMAS_CEF_SEGURO_AMBITO',
            'ROLE_SISTEMAS_CEF_SEGURO_TRANSPORTE',
            'ROLE_SISTEMAS_CEF_SEGURO_FRANQUICIA',
            'ROLE_SISTEMAS_CEF_SEGURO_TARIFA',
            'ROLE_SISTEMAS_CEF_DOCUMENTACION',
            'ROLE_SISTEMAS_CEF_TIPO_EMPRESA',
            'ROLE_ADMIN_PUBLI_CCF',
            'ROLE_ADMIN_RIESGOS_CCF',
            'ROLE_AREA_COMERCIAL',
            'ROLE_AREA_COMERCIAL_RESPONSABLE',
            'ROLE_AREA_RIESGOS',
            'ROLE_RIESGOS_CCF',
            'ROLE_USER_VIAJES',
            'ROLE_PUBLI_CCF',
            'ROLE_SISTEMAS_CEF',
            'ROLE_SUPER_ADMIN',
            'ROLE_SUPER_USER',
            'ROLE_USER',
            'ROLE_ADMIN_GUEST',
        ];
        foreach ($content as $value) {
            $count++;
            $content = new Role();
            $content->setRoleName($value);
            $manager->persist($content);

            $this->addReference(Role::class.'_'.$count, $content);
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
