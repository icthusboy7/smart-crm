<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixture
 */
class UserFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
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
     *
     * @throws \Exception
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
                'company_id'            => 1,
                'role_id'               => 21,
                'username'              => 'sisadmin',
                'username_canonical'    => 'sisadmin',
                'email'                 => 'sisadmin@example.org',
                'email_canonical'       => 'sisadmin@example.org',
                'enabled'               => '1',
                'salt'                  => null,
                'last_login'            => null,
                'confirmation_token'    => null,
                'password_requested_at' => null,
                'roles'                 => ['N;'],
                'reg_number'            => 'sisadmin',
                'reg_number_caixabank'  => 'sisadmin',
                'name'                  => 'SisAdmin',
                'surname'               => 'Itteria',
                'createdAt'             => '2019-08-14 11:23:00',
                'updatedAt'             => '2019-08-14 11:24:09',
                'timesLogged'           => '0',
                'flagNotify'            => '1',
                'password'              => '12345',
            ],
            [
                'company_id'            => 2,
                'role_id'               => 21,
                'username'              => 'admin2',
                'username_canonical'    => 'admin2',
                'email'                 => 'admin2@example.org',
                'email_canonical'       => 'admin2@example.org',
                'enabled'               => '1',
                'salt'                  => null,
                'last_login'            => null,
                'confirmation_token'    => null,
                'password_requested_at' => null,
                'roles'                 => ['N;'],
                'reg_number'            => 'admin2',
                'reg_number_caixabank'  => 'admin2',
                'name'                  => 'Admin2',
                'surname'               => 'Itteria',
                'createdAt'             => '2019-08-14 11:23:00',
                'updatedAt'             => '2019-08-14 11:24:09',
                'timesLogged'           => '0',
                'flagNotify'            => '1',
                'password'              => '12345',
            ],
        ];
        $count   = 0;
        foreach ($content as $key => $row) {
            $count++;
            $content = new User();
            foreach ($row as $column => $value) {
                if ('role_id' === $column) {
                    $content->setRole(
                        $this->getReference(Role::class.'_'.$value)
                    );
                } elseif ('password' === $column) {
                    $content->setPassword(
                        $this->encoder->encodePassword($content, $value)
                    );
                } elseif ('company_id' === $column) {
                    $content->setCompany(
                        $this->getReference(Company::class.'_'.random_int(1, 3))
                    );
                } elseif (isset($columnMapFunction[$column])) {
                    $content->{$columnMapFunction[$column]}($value);
                }
            }
            $manager->persist($content);
            $this->addReference(User::class.'_'.$count, $content);
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
            'test',
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
            CompanyFixture::class,
            RoleFixture::class,
        ];
    }
}
