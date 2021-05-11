<?php

namespace App\DataFixtures;

use App\Entity\Charge;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\Reason;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\Vertical;
use App\Entity\Visit;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SalesVisitFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 40;
    private const MAX_USERS          = 2;
    private const MAX_STATUS         = 3;
    private const MAX_VISIT_REASON   = 3;
    private const MAX_CHARGE         = 4;
    private const MAX_EXP_REASON     = 2;
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
            VerticalFixture::class,
            ReasonFixture::class,
            StatusFixture::class,
            UserFixture::class,
            ChargeFixture::class,
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
        // TODO: Implement getGroups() method.
        return [
            'dev',
            'pre',
        ];
    }
    /**
     * @param ObjectManager $manager
     *
     * @return mixed|void
     */
    protected function loadData(ObjectManager $manager): void
    {
        $users = $this->getReferences(User::class);

        $class = $this;
        $this->createMany(
            Visit::class,
            self::NUM_ELEMENTS_ADDED,
            static function (Visit $content, $count) use ($class, $manager, $users): void {
                $content
                    ->setObservations($class->faker->text())
                    ->setDuration('01:00')
                    ->setStatus($class->getReference(Status::class.'_'.random_int(1, self::MAX_STATUS)))
                    ->setVertical($class->getReference(Vertical::class.'_'.random_int(1, self::MAX_VERTICAL)))
                    ->setReason($class->getReference(Reason::class.'_'.random_int(1, self::MAX_EXP_REASON)))
                    ->setType($class->faker->boolean(10))
                    ->setFeedback($class->faker->optional(0.2)->text())
                    ->setDateIni($randStartDate = $class->faker->dateTimeBetween('now', '+1 month'))
                    ->setDateFin($class->faker->dateTimeInInterval($randStartDate->format('Y-m-d H:i:s'), '+ 1 hour'))
                    ->setDateIni($randStartDate = $class->faker->dateTimeBetween('now', '+1 month'))
                    ->setDateFin($class->faker->dateTimeInInterval($randStartDate->format('Y-m-d H:i:s'), '+ 1 hour'))
                    ->setAuthor($class->faker->randomElement($users));
                ;
                if (null !== $class->faker->optional(0.9)->randomDigit) {
                    $content
                        ->setOffice(self::getRandOffice($manager))
                    ;
                }
                if (null !== $class->faker->optional(0.9)->randomDigit) {
                    $content
                        ->setProviderID(self::getRandPrescriberCIF($manager))
                        ->setProviderCharge($class->getReference(Charge::class.'_'.random_int(1, self::MAX_CHARGE)))
                    ;
                }
                if (null !== $class->faker->optional(0.9)->randomDigit) {
                    $content
                        ->setCustomerID(self::getRandClientNIF($manager))
                        ->setCustomerCharge($class->getReference(Charge::class.'_'.random_int(1, self::MAX_CHARGE)));
                }
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
