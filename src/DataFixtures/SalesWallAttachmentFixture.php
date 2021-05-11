<?php

namespace App\DataFixtures;

use App\Entity\ComercialMuro;
use App\Entity\ComercialMuroAdjuntos;
use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SalesWallAttachmentFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 50;
    private const MAX_MURO           = 70;
    private const MAX_DOCUMENT       = 100;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            DocumentFixture::class,
            SalesWallFixture::class,
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
            ComercialMuroAdjuntos::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialMuroAdjuntos $content, $count) use ($class, $manager): void {
                $content
                    ->setMuro($class->getReference(ComercialMuro::class.'_'.random_int(1, self::MAX_MURO)))
                    ->setDocId($class->getReference(Document::class.'_'.random_int(1, self::MAX_DOCUMENT)))
                    ->setExt($class->faker->fileExtension)
                    ->setFilename($class->faker->md5)
                    ->setMimeType($class->faker->mimeType)
                    ->setCreatedAt($createdAt = $class->faker->dateTimeBetween('now', '+6 months'))
                    ->setUpdatedAt($class->faker->dateTimeInInterval($createdAt->format('Y-m-d H:i:s'), '+ 1 day'))
                ;
            }
        );

        $manager->flush();
    }
}
