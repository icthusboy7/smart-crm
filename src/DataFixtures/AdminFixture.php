<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminFixture
 */
class AdminFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AdminFixture constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var array $columnMapFunction */
        $columnMapFunction = [
            'company_id'            => 'setCompany',
            'role_id'               => 'setRoles',
            'username'              => 'setName',
            'username_canonical'    => 'setName',
            'email'                 => 'setEmail',
            'email_canonical'       => 'setEmailCanonical',
            'enabled'               => 'setEnabled',
            'salt'                  => 'setSalt',
            'password'              => 'setPassword',
            'last_login'            => 'setLastLogin',
            'confirmation_token'    => 'setConfirmationToken',
            'password_requested_at' => 'setPasswordRequestedAt',
            'roles'                 => 'setRoles',
            'reg_number'            => 'setRegNumber',
            'reg_number_caixabank'  => 'setRegNumberCaixabank',
            'name'                  => 'setName',
            'surname'               => 'setSurname',
            'createdAt'             => null,
            'updatedAt'             => null,
            'timesLogged'           => 'setTimesLogged',
            'flagNotify'            => 'setFlagNotify',
        ];
        /** @var array $content */
        $content = [
            [
                'company_id'            => null,
                'role_id'               => 1,
                'username'              => 'admin1',
                'username_canonical'    => 'admin1',
                'email'                 => 'admin1@example.org',
                'email_canonical'       => 'admin1@example.org',
                'enabled'               => '1',
                'salt'                  => null,
                'last_login'            => null,
                'confirmation_token'    => null,
                'password_requested_at' => null,
                'roles'                 => ['N;'],
                'reg_number'            => 'admin1',
                'reg_number_caixabank'  => 'admin1',
                'name'                  => 'Admin1',
                'surname'               => 'Itteria',
                'createdAt'             => '2019-08-14 11:23:00',
                'updatedAt'             => '2019-08-14 11:24:09',
                'timesLogged'           => '0',
                'flagNotify'            => '1',
                'password'              => '12345',
            ],
        ];

        foreach ($content as $key => $row) {
            $content = new Admin();
            foreach ($row as $column => $value) {
                if ('role_id' === $column) {
                    $content->setRole(
                        // We need a reference to a Role created during RoleFixture
                        $this->getReference(Role::class.'_'.$value)
                    );
                } elseif ('password' === $column) {
                    $pass = $this->encoder->encodePassword($content, $value);
                    $content->setPassword(
                        $this->encoder->encodePassword($content, $pass)
                    );
                } elseif (isset($columnMapFunction[$column])) {
                    $content->{$columnMapFunction[$column]}($value);
                }
            }
            $manager->persist($content);
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
        // TODO: Implement getGroups() method.
        return [
            'dev',
            'pre',
            'admin',
        ];
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            RoleFixture::class,
        ];
    }
}
