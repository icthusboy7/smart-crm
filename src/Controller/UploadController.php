<?php
/**
 * Controlador de subida de archivos y ejecucion de comando desde SonataAdmin
 */

namespace App\Controller;

use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Service\FileUploader;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class UploadController
 */
class UploadController extends AbstractController
{
    /**
     * Subida de archivo de traducciones desde SonataAdmin
     * @param Request         $request
     * @param string          $uploadDirTranslate
     * @param string          $uploadDirMaestros
     * @param FileUploader    $uploader
     * @param LoggerInterface $logger
     *
     * @return Response
     */
    public function index(Request $request, string $uploadDirTranslate, string $uploadDirMaestros, FileUploader $uploader, LoggerInterface $logger): Response
    {
        $token = $request->get('token');

        if (!$this->isCsrfTokenValid('upload', $token)) {
            $logger->info('CSRF failure');

            return new Response(
                'Operation not allowed',
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']
            );
        }

        $file = $request->files->get('myfile');

        if (empty($file)) {
            return new Response(
                'No file specified',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['content-type' => 'text/plain']
            );
        }
        $filename = $file->getClientOriginalName();
        if ($request->get('form_name') === 'translation') {
            $lang = $request->request->get('language');
            if (!is_null($lang)) {
                $filename = 'messages.'.$lang.'.po';
            }
            $uploader->upload($uploadDirTranslate, $file, $filename);
        } elseif ($request->get('form_name') === 'maestros') {
            $uploader->upload($uploadDirMaestros, $file, $filename);
        }

        return new Response(null, Response::HTTP_OK);
    }

    /**
     * Funcion para ejecutar comando desde SonataAdmin
     * Recibe comando que debe lanzar, el delimitador, el nombre del archivo a importar y si es lectura o escritura
     * @param KernelInterface $kernel
     * @param Request         $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function executeCommand(KernelInterface $kernel, Request $request): Response
    {
        $type = $request->request->get('type');

        $filename = $request->request->get('files_command');

        $delimiter = ord($request->request->get('delimiter'));

        $application = new Application($kernel);
        $application->setAutoExit(false);
        $commandToRun = $request->request->get('type_command');

        $input = new ArrayInput([
            'command'   => 'app:data',
            // (optional) define the value of command arguments
            'type'      => $commandToRun,
            'filename'  => $filename,
            'status'    => $type,
            'delimiter' => $delimiter,
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(''), if you used NullOutput()
        return new Response($content);
    }

    /**
     * Delete upload file
     * @param KernelInterface $kernel
     * @param Request         $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function deleteFile(KernelInterface $kernel, Request $request): Response
    {
        $dir       = $kernel->getProjectDir();
        $directory = getenv('FILES_DIRECTORY');
        $file      = $request->get('files_command');
        if ($file) {
            $path = $dir.'/'.$directory.'/'.$file;
            try {
                $filesystem = new Filesystem();
                $filesystem->remove(['symlink', $path]);
            } catch (Exception $e) {
                throw $e;
            }
        }

        return new Response();
    }

    /**
     * Send to Rabbit to import data file into database
     *
     * @param Request            $request
     * @param ContainerInterface $container
     *
     * @return Response
     */
    public function pushCommand(Request $request, ContainerInterface $container): Response
    {

        // Sent to RabbitMQ File and t
        try {
            $container->get('old_sound_rabbit_mq.uploads_producer');
            $container->get('old_sound_rabbit_mq.uploads_producer')->setContentType('application/json');
            $container->get('old_sound_rabbit_mq.uploads_producer')->publish(
                json_encode([
                    'import'    => $request->get('type_command'),
                    'file'      => $request->get('files_command'),
                    'delimiter' => $request->get('delimiter'),
                    'type'      => $request->get('type'),
                ])
            );
        } catch (Exception $exception) {
            $this->logger->error('Error Send Message With in Wall Service', [$exception->getMessage()]);

            return new Response('error');
        } catch (\Error $error) {
            $this->logger->error('Error Send Message With in Wall Service', [$error->getMessage()]);

            return new Response('error');
        }

        return new Response('sended');
    }
}
