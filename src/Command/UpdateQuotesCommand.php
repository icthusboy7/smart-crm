<?php

namespace App\Command;

use App\Entity\ComercialCotizacion;
use App\Service\SAPConnectorService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateQuotesCommand extends Command
{
    /* State list to check during daily process */
    private const STATESQUOTEDAILY = ['Denegada', 'Cancelado', 'Desistida', 'Vencido', 'Vigente', 'Vigente y Archivada', 'Vigente y archivada'];

    /* State list to check during hourly process */
    private const STATESQUOTEHOUR = ['Activable', 'Incidencia'];

    /**
     * Name command
     * @var string
     */
    protected static $defaultName = 'app:update-quotes';

    /**
     * @var object
     */
    private $em;

    /**
     * @var object
     */
    private $sapService;

    /**
     * @var object
     */
    private $logger;

    /**
     * Update quotes constructor.
     *
     * @param EntityManagerInterface $em
     * @param SAPConnectorService    $sapService
     * @param LoggerInterface        $logger
     */
    public function __construct(EntityManagerInterface $em, SAPConnectorService $sapService, LoggerInterface $logger)
    {
        $this->em         = $em;
        $this->sapService = $sapService;
        $this->logger     = $logger;
        parent::__construct();
    }

    /**
     * Configure Command
     */
    protected function configure(): void
    {
        $this->setDescription('Update quotes');
        parent::configure();
    }

    /**
     * Execute command to hourly update quotes
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $quotes = $this->em->getRepository(ComercialCotizacion::class)->findBy(['estado' => $this::STATESQUOTEHOUR]);
        foreach ($quotes as $quote) {
            $quoteSAP = $this->sapService->getQuoteData($quote->getNumCoti());
            if ('NOK' === $quoteSAP or is_null($quoteSAP)) {
                continue;
            }
            if ('NOK' === $quoteSAP->EF_RESPUESTA) {
                continue;
            }

            $dataQuote = json_decode($quoteSAP->EF_DATOS);

            if ($quote->getEstado() === $dataQuote->sDocVta->estadoTxt) {
                continue;
            }
            try {
                $quoteClone = clone($quote);

                $fechaEstado = \DateTime::createFromFormat('Y-m-d', $dataQuote->sDocVta->fCambioEstado);
                $fechaEstado->setTime(0, 0, 0);

                $quote->setEstado($dataQuote->sDocVta->estadoTxt);
                $quote->setFechaEstado($fechaEstado);
                $quote->setUpdatedAt(new \DateTime('now'));

                $this->em->persist($quote);

                $this->compareQuoteChanges($quote, $quoteClone);
            } catch (\Exception $e) {
                $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());
            }
        }
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());
        }
    }

    /**
     * @param ComercialCotizacion $quoteNew
     * @param ComercialCotizacion $quoteOld
     */
    private function compareQuoteChanges(ComercialCotizacion $quoteNew, ComercialCotizacion $quoteOld): void
    {
        if ($quoteNew->getEstado() !== $quoteOld->getEstado()) {
            // @TODO: Send this to wall service to create a wall message and send notification
        }
    }
}
