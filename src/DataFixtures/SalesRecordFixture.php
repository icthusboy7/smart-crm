<?php

namespace App\DataFixtures;

use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedientePerdidoMotivo;
use App\Entity\ComercialExpedienteStatus;
use App\Entity\ComercialProducto;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\TipoCanal;
use App\Entity\User;
use App\Entity\Vertical;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SalesRecordFixture
 */
class SalesRecordFixture extends AbstractBaseFixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const NUM_ELEMENTS_ADDED    = 100;
    private const DFT_TITLE_START       = 'Expediente';
    private const DFT_RESP_CAIXA        = 'Responsable Caixa';
    private const MAX_USERS             = 2;
    private const MAX_EXP_STATUS        = 3;
    private const MAX_EXP_STATUS_REASON = 4;
    private const MAX_PRODUCTS          = 5;
    private const MAX_CH_TYPE           = 2;
    private const MAX_VERTICAL          = 7;
    private const MAX_OFFICES           = 6287;
    private const MAX_CLIENTS           = 90;
    private const MAX_PROVIDERS         = 100;

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
            SalesExpLostReasonFixture::class,
            SalesExpStatusFixture::class,
            SalesProductFixture::class,
            ChanelTypeFixture::class,
            UserFixture::class,
            VerticalFixture::class,
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
            'test',
        ];
    }
    /**
     * @param ObjectManager $manager
     *
     * @return mixed|void
     */
    protected function loadData(ObjectManager $manager): void
    {
        $class = $this;
        $this->createMany(
            ComercialExpediente::class,
            self::NUM_ELEMENTS_ADDED,
            static function (ComercialExpediente $expediente, $count) use ($class, $manager): void {
                $expediente
                    ->setTitulo(self::DFT_TITLE_START.$count)
                    ->setPorcentajeProbabilidad(self::getChance())
                    ->setResponsableCaixa(self::DFT_RESP_CAIXA)
                    ->setImporte(self::getImport())
                    ->setFechaPosibleActivacion(self::getDateActivation())
                    ->setFechaOportunidad($class->faker->dateTimeBetween('-3 months'))
                    ->setTipo((string) (random_int(100, 500) / 100))
                    ->setTin(random_int(1, 5))
                    ->setEslinea(random_int(0, 1))
                    ->setStatus($class->getReference(ComercialExpedienteStatus::class.'_'.random_int(1, self::MAX_EXP_STATUS)))
                    ->setStatusMotivo($class->getReference(ComercialExpedientePerdidoMotivo::class.'_'.random_int(1, self::MAX_EXP_STATUS_REASON)))
                    ->setProductoID($class->getReference(ComercialProducto::class.'_'.random_int(1, self::MAX_PRODUCTS)))
                    ->setCanal($class->getReference(TipoCanal::class.'_'.random_int(1, self::MAX_CH_TYPE)))
                    ->setVertical($class->getReference(Vertical::class.'_'.random_int(1, self::MAX_VERTICAL)))
                    ->setClienteNIF(self::getRandClientNIF($manager))
                    ->setPrescriptorCIF(self::getRandPrescriberCIF($manager))
                    ->setOficina($office = self::getRandOffice($manager)[0])
                    ->setOficinaZona($office[1])
                    ->setResponsable($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS))) //ha de ser comercial
                    ->setResponsableGestorInterno($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS))) //ha de ser gestor
                    ->setResponsableGestorExterno($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS))) //ha de ser gestor
                    ->setResponsableRiesgos($class->getReference(User::class.'_'.random_int(1, self::MAX_USERS)))
                    ->setObservaciones($class->faker->text(200))
                    ->setNoReport(random_int(0, 1))
                    ->setEstado($class->faker->randomElement(['ABIERTO', 'ACEPTADO', 'CERRADO', 'CANCELADO']))
                    ->setImporteDisponible(self::getImport())
                    ->setImporteLimite((float) self::getImport())
                    ->setFechaVencimiento($class->faker->dateTimeBetween('now', '+6 months'))
                    ->setFechaEstado($class->faker->dateTimeBetween('-1 months'))
                    ->setNumLinea((string) random_int(10000, 20000))
                ;
            }
        );

        $manager->flush();
    }

    /**
     * @return int
     *
     * @throws \Exception
     */
    private function getChance(): int
    {
        return (int) (random_int(1, 10) * 10);
    }
    /**
     * @return int
     *
     * @throws \Exception
     */
    private function getImport(): int
    {
        return (random_int(10, 100) * 100000);
    }
    /**
     * @return string
     *
     * @throws \Exception
     */
    private function getDateActivation(): string
    {
        return random_int(1, 12).'/'.random_int(2019, 2022);
    }

    /**
     * @param ObjectManager $manager
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getRandOffice(ObjectManager $manager): array
    {
        $office = $manager->find(MasterOffice::class, (int) random_int(1, self::MAX_OFFICES));

        return [
            $office->getCodigo(),
            $office->getDt(),
        ];
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
