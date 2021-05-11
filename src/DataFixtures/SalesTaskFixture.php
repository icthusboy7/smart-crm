<?php

namespace App\DataFixtures;

use App\Entity\ComercialMuro;
use App\Entity\ComercialTask;
use App\Entity\ComercialTaskStatus;
use App\Entity\ComercialTaskType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SalesTaskFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 40;
    private const DFT_TITLE_START    = 'Task';
    private const MAX_USERS          = 2;
    private const MAX_TASK_STATUS    = 4;
    private const MAX_TASK_TYPES     = 4;
    private const MAX_MURO           = 70;

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
            SalesTaskTypeFixture::class,
            SalesTaskStatusFixture::class,
            SalesWallFixture::class,
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
            'test',
            'dev',
        ];
    }
    /**
     * @param ObjectManager $manager
     *
     * @return mixed|void
     */
    protected function loadData(ObjectManager $manager): void
    {
        $cmEntities = $this->getReferences(ComercialMuro::class);
        shuffle($cmEntities);

        $class = $this;
        $this->createMany(
            ComercialTask::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialTask $content, $count) use ($class, $manager, &$cmEntities): void {
                $content
                    ->setSeen($class->faker->boolean)
                    ->setDescription(self::DFT_TITLE_START.$count.' '.$class->faker->text(100))
                    ->setType($class->getReference(ComercialTaskType::class.'_'.random_int(1, self::MAX_TASK_TYPES)))
                    ->setStatus($class->getReference(ComercialTaskStatus::class.'_'.random_int(1, self::MAX_TASK_STATUS)))
                    ->setCreatedBy($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                ;

                if ($class->faker->optional(0.8)->randomDigit) {
                    $content->setResponsible($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)));
                }
                if ($class->faker->optional(0.9)->randomDigit) {
                    $content
                        ->setDeletedBy($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                        ->setDeletedAt($class->faker->dateTimeBetween('now', '+6 months'))
                    ;
                }
                if ($class->faker->optional(0.2)->randomDigit) {
                    return;
                }

                $content->setComercialMuro(array_pop($cmEntities));
            }
        );

        $manager->flush();
    }
}
