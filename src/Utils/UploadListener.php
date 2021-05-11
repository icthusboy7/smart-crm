<?php
/**
 * Upload listener Utils
 */
namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Oneup\UploaderBundle\Event\PostPersistEvent;

/**
 * Class UploadListener
 * @package App\Utils
 */
class UploadListener
{
    /**
     * Entity manager variable
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * UploadListener constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * files upload function
     * @param PostPersistEvent $event
     * @return \Oneup\UploaderBundle\Uploader\Response\ResponseInterface
     */
    public function onUpload(PostPersistEvent $event)
    {
        //...

        //if everything went fine
        $response = $event->getResponse();
        $response['success'] = true;
        return $response;
    }
}
