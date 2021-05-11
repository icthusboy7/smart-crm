<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Synchronize the database with the master tables.
 */
class SynchCommand extends Command
{
    /**
     * Default command name
     * @var string
     */
    protected static $defaultName = 'app:data:synch';

    /**
     * Entity manager
     * @var EntityManager
     */
    private $manager;


    /**
     * Object constructor.
     *
     * @param EntityManagerInterface $manager Entity manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }


    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setDescription('Synchronizes the database tables');
        $this->setHelp('Synchronize the database with the master tables');
    }


    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $watch = new Stopwatch();
        $io = new SymfonyStyle($input, $output);
        $connection = $this->manager->getConnection();

        $io->title('Executing data synchronizations');
        $watch->start('synchronize');

        $io->text('> Synchronize contacts');
        $query = $connection->prepare('call synch_contacts()');
        $query->execute();

        $event = $watch->stop('synchronize');
        $seconds = $event->getDuration() / 1000.;

        $io->success(sprintf('Synchronized in %f seconds', $seconds));
    }
}
