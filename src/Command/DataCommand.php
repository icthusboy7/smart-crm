<?php
/**
 * Created by PhpStorm.
 * User: visai
 * Date: 14/03/2019
 * Time: 9:56
 */

namespace App\Command;

use App\Utils\DataReader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DataCommand
 */
class DataCommand extends Command
{
    /**
     * Nombre del comando
     * @var string
     */
    protected static $defaultName = 'app:data';

    /**
     * Inyeccion DataReader
     * @var DataReader
     */
    private $data;

    /**
     * DataCommand constructor.
     * @param DataReader $data
     */
    public function __construct(DataReader $data)
    {
        $this->data = $data;

        parent::__construct();
    }

    /**
     * Establecer parametros para lanzar el comando
     */
    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Imports a data')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you import data')
            ->addArgument('type', InputArgument::REQUIRED, 'Type Action')
            ->addArgument('filename', InputArgument::REQUIRED, 'File name')
            ->addArgument('delimiter', InputArgument::REQUIRED, 'Delimiter')
            ->addArgument('status', InputArgument::REQUIRED, 'status Action')
        ;
    }

    /**
     * Ejecutar conmando recibiendo un input y devolviendo el output
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $type      = $input->getArgument('type');
        $file      = $input->getArgument('filename');
        $delimiter = $input->getArgument('delimiter');
        $status    = $input->getArgument('status');

        $out = $this->data->getAction($type, $file, $delimiter, $status);

        $output->write($out);
    }
}
