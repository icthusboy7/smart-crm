<?php

namespace App\Rabbit;

use \Exception;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

use App\Utils\DataReader;

/**
 * Class UploadsService
 */
class UploadsService implements ConsumerInterface
{
    /**
     * Inyeccion LoggerInterface
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var DataReader
     */
    private $dataReader;

    /**
     * UploadsService constructor.
     * @param LoggerInterface $logger
     * @param DataReader      $dataReader
     */
    public function __construct(LoggerInterface $logger, DataReader $dataReader)
    {
        $this->logger     = $logger;
        $this->dataReader = $dataReader;
    }

    /**
     * @param AMQPMessage $msg
     *
     * @throws Exception
     */
    public function execute(AMQPMessage $msg): void
    {
        $response = json_decode($msg->body, true);
        $this->doCommand($response);
    }

    /**
     * @param array $response
     *
     * @throws Exception
     */
    public function doCommand(array $response): void
    {
        $this->dataReader->getAction($response['import'], $response['file'], ord($response['delimiter']), $response['type']);
    }
}
