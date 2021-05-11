<?php


namespace App\Command;

use \Exception;

use App\Entity\ComercialMuro;

use App\Service\MuroService;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTextWallCommand extends Command
{
    /**
     * Name del command
     * @var string
     */
    protected static $defaultName = 'app:changeWallStatusText';

    /**
     * @var object
     */
    private $em;

    /**
     * @var object
     */
    private $wallService;

    /**
     * uploadDocuCommand constructor.
     *.
     * @param EntityManagerInterface $em
     */
    /**
     * UpdateTextWallCommand constructor.
     * @param EntityManagerInterface $em
     * @param MuroService            $wallService
     */
    public function __construct(EntityManagerInterface $em, MuroService $wallService)
    {
        $this->em          = $em;
        $this->wallService = $wallService;
        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $messagesStatus = $this->em->getRepository(ComercialMuro::class)->findBy(['tipo' => 4]);

        foreach ($messagesStatus as $message) {
            $updatedMessage = (strpos($message->getMissatge(), '<span class="campoModificado">'))
                ? $this->getNewMessage($message->getMissatge())
                : '';

            $returned = ('' !== $updatedMessage) ? $this->saveUpdate($message, $updatedMessage) : false;

            (true === $returned) ? var_dump('message id '.$message->getId().' has updated') : '';
        }
    }

    /**
     * @param string $oldMessage
     *
     * @return string
     */
    private function getNewMessage(string $oldMessage): string
    {
        $fieldModify = strstr(strstr(strstr($oldMessage, 'campoModificado', false), '</span>', true), '>', false);
        $fieldModify = str_replace('>', '', $fieldModify);

        $valueOld = strstr(strstr(strstr($oldMessage, 'valorAntiguo', false), '</span>', true), '>', false);
        $valueOld = str_replace('>', '', $valueOld);
        $valueNew = strstr(strstr(strstr($oldMessage, 'valorNuevo', false), '</span>', true), '>', false);
        $valueNew = str_replace('>', '', $valueNew);

        return ($valueNew !== '' || $valueOld !== '')
            ? $this->wallService->textStatusMessagePipeline($valueOld, $valueNew, $fieldModify)
            : '';
    }

    /**
     * @param ComercialMuro $message
     * @param string        $updatedMessage
     *
     * @return bool
     */
    private function saveUpdate(ComercialMuro $message, string $updatedMessage): bool
    {
        $message->setMissatge($updatedMessage);
        try {
            $this->em->persist($message);
            $this->em->flush();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
