<?php

namespace App\DataFixtures;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DocumentFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED = 100;
    private const MAX_USERS          = 2;
    private const MAX_QUOTES         = 50;
    private const MAX_RECORDS        = 100;
    private const MAX_OFFICES        = 6287;
    private const MAX_CLIENTS        = 90;
    private const MAX_PROVIDERS      = 100;
    private const MAX_DOC_TYPES      = 18;

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
            SalesQuoteFixture::class,
            UserFixture::class,
            DocumentTypeFixture::class,
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
            Document::class,
            self::NUM_ELEMENTS_ADDED,
            static function (Document $content, $count) use ($class, $manager): void {
                $content
                    ->setCreatedAt($createdAt = $class->faker->dateTimeBetween('now', '+6 months'))
                    ->setUser($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setObservations($class->faker->realText($class->faker->numberBetween(10, 20)))
                    ->setName($class->faker->word().'_'.(string) $class->faker->numberBetween(876543, 1000000))
                    ->setDocumentType($class->getReference(DocumentType::class.'_'.random_int(1, self::MAX_DOC_TYPES)))
                    ->setIdDoku($class->faker->uuid)
                    ->setPath(null)
                ;
                if ($class->faker->boolean(20)) {
                    $content->setQuotation((string) $class->getReference(ComercialCotizacion::class.'_'.random_int(1, self::MAX_QUOTES))->getNumCoti());
                }
                if ($class->faker->boolean(20)) {
                    $content->setExpedient($class->getReference(ComercialExpediente::class.'_'.random_int(1, self::MAX_RECORDS)));
                }
                if ($class->faker->boolean(20)) {
                    $content->setCustomer(self::getRandClientNIF($manager));
                }
                if ($class->faker->boolean(20)) {
                    $content->setProvider(self::getRandPrescriberCIF($manager));
                }
                if ($class->faker->boolean(20)) {
                    return;
                }
                $content->setOffice(self::getRandOffice($manager));
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
