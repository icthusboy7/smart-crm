<?php

namespace App\Command;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocumentMigrationCommand extends Command
{
    /* State list to check during daily process */
    private const STATESQUOTEDAILY = ['Denegada', 'Cancelado', 'Desistida', 'Vencido', 'Vigente', 'Vigente y Archivada', 'Vigente y archivada'];

    /* State list to check during hourly process */
    private const STATESQUOTEHOUR = ['Activable', 'Incidencia'];

    /**
     * Name command
     * @var string
     */
    protected static $defaultName = 'app:document-migration';

    /**
     * @var object
     */
    private $em;

    /**
     * Update quotes constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * Configure Command
     */
    protected function configure(): void
    {
        $this->setDescription('Documents migration')
        ->setHelp('This command allows you convert migrate file documents')
        ->addArgument('pathOrigin', InputArgument::REQUIRED, 'origin')
        ->addArgument('pathDestination', InputArgument::REQUIRED, 'destination')
        ;
    }

    /**
     * Execute command to adapted migrated file documents
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $pathOrigin      = $input->getArgument('pathOrigin');
        $pathDestination = $input->getArgument('pathDestination');

        $documents = $this->em->getRepository(Document::class)->findPathNoNull();
        $iError    = 1;
        foreach ($documents as $document) {
            $documentId   = $document->getId();
            $filename     = $document->getName();
            $documentPath = $document->getPath();

            $dirDestination = $pathDestination.DIRECTORY_SEPARATOR.$documentId.DIRECTORY_SEPARATOR;

            $fileOrigin      = $pathOrigin.DIRECTORY_SEPARATOR.$documentPath.'_-_'.$filename;
            $fileDestination = $dirDestination.$filename;

            if (file_exists($fileOrigin)) {
                mkdir($dirDestination, 0755, true);
                copy($fileOrigin, $fileDestination);

                $docu = $this->em->getRepository(Document::class)->findOneBy(['id' => $document->getId()]);
                $docu->setPath(null);
                $this->em->persist($docu);
                unlink($fileOrigin);
            } else {
                $iError === 1 ? $output->write("File with errors:\n") : '';
                $output->write("{$iError} {$fileOrigin}\n");
                $iError++;
            }
        }
        $this->em->flush();
        $this->em->clear();

        $output->write("Document migration finalized!\n");
    }
}
