<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

/**
 * Class FileUploader
 * @package App\Service
 */
class FileUploader
{
    /**
     * Inyección LoggerInterface
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FileUploader constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * upload files to a directory
     * @param $uploadDir
     * @param $file
     * @param $filename
     */
    public function upload($uploadDir, $file, $filename)
    {
        try {
            $file->move($uploadDir, $filename);
        } catch (FileException $e) {
            $this->logger->error('failed to upload file: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }
}
