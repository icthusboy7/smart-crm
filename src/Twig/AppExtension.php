<?php

namespace App\Twig;

use App\Core\Entity\Contact;
use App\Entity\MasterOffice;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AppExtension constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getCustomerName', [$this, 'getCustomerName']),
            new TwigFunction('getProviderName', [$this, 'getProviderName']),
            new TwigFunction('getOficinaName', [$this, 'getOficinaName']),
        ];
    }

    /**
     * @param string $nif
     *
     * @return mixed
     */
    public function getPersonaName(string $nif)
    {
        $persona = $this->entityManager->getRepository(Contact::class)->findOneBy(['nif' => $nif]);

        return $persona->getName();
    }

    /**
     * @param string $nif
     *
     * @return mixed
     */
    public function getCustomerName(string $nif)
    {
        return $this->getPersonaName($nif);
    }

    /**
     * @param string $nif
     *
     * @return mixed
     */
    public function getProviderName(string $nif)
    {
        return $this->getPersonaName($nif);
    }
    /**
     * @param string $codigo
     *
     * @return string
     */
    public function getOficinaName(string $codigo): string
    {
        $oficina = $this->entityManager->getRepository(MasterOffice::class)->findOneBy(['codigo' => $codigo]);

        return $oficina->getNombre();
    }
}
