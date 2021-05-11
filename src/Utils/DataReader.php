<?php

namespace App\Utils;

use \Exception;
use App\Entity\CommercialOffice;
use App\Entity\CommercialResponsable;
use App\Entity\GestorHorizontal;
use App\Entity\GestorResponsable;
use App\Entity\MasterCustomer;
use App\Entity\MasterEmployee;
use App\Entity\MasterFamily;
use App\Entity\MasterFamilyRel;
use App\Entity\MasterFamilySub;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\MasterQuotation;
use App\Entity\MasterRequest;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Common\Helper\GlobalFunctionsHelper;
use Box\Spout\Common\Type;
use Box\Spout\Reader\CSV\Reader as CSV;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Box\Spout\Reader\ReaderFactory;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Psr\Log\LoggerInterface;

/**
 * Class DataReader
 * @namespace App\Utils
 */
class DataReader
{
    /**
     * Inyeccion EntityManager
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Inyeccion LoggerInterface
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * Variable de output
     * @var string
     */
    private $out;

    /**
     * Variable container
     * @var ContainerInterface
     */
    private $container;

    /**
     * DataReader constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface     $container
     * @param LoggerInterface|null   $logger
     */
    public function __construct(?LoggerInterface $logger = null, EntityManagerInterface $em, ContainerInterface
$container)
    {
        $this->em        = $em;
        $this->container = $container;
        $this->logger    = $logger;
    }

    /**
     * Get the files to import
     * @param string $type
     * @param string $file
     * @param string $delimiter
     * @param string $status
     *
     * @return bool|string|null
     *
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     * @throws DBALException
     */
    public function getAction(string $type, string $file, string $delimiter, string $status)
    {
        $path     = $this->container->getParameter('data_import');
        $filepath = $path.$file;
        $output   = null;
        $delimit  = chr($delimiter);

        if ('R' === $status) {
            if (in_array($type, ['oficinas', 'empleados'])) {
                $output = $this->reader($type, $delimit, $file, $filepath, 9);
            } elseif (in_array($type, ['proveedores', 'clientes'])) {
                $output = $this->reader($type, $delimit, $file, $filepath, 0);
            } elseif ('familia' === $type) {
                $output = $this->reader($type, $delimit, $file, $filepath, 1);
            } elseif ('familiaSub' === $type) {
                $output = $this->reader($type, $delimit, $file, $filepath, 24);
            } elseif (in_array($type, ['familiaRel', 'cotizaciones'])) {
                $output = $this->reader($type, $delimit, $file, $filepath, 2);
            } elseif ('solicitudes' === $type) {
                $output = $this->reader($type, $delimit, $file, $filepath, 7);
            } elseif ('comerciales' === $type) {
                $output = $this->readerCommercial($type, $file, $filepath);
            } elseif ('gestores' === $type) {
                $output = $this->readerGestor($type, $file, $filepath);
            }

            return $output;
        }

        if ('W' === $status) {
            switch ($type) {
                case 'oficinas':
                    $output = $this->writer($type, $delimit, $file, $filepath, 9);
                    break;
                case 'empleados':
                    $output = $this->writer($type, $delimit, $file, $filepath, 9);
                    break;
                case 'proveedores':
                    $output = $this->writer($type, $delimit, $file, $filepath, 0);
                    break;
                case 'clientes':
                    $output = $this->writer($type, $delimit, $file, $filepath, 0);
                    break;
                case 'familia':
                    $output = $this->writer($type, $delimit, $file, $filepath, 1);
                    break;
                case 'familiaSub':
                    $output = $this->writer($type, $delimit, $file, $filepath, 24);
                    break;
                case 'familiaRel':
                    $output = $this->writer($type, $delimit, $file, $filepath, 2);
                    break;
                case 'solicitudes':
                    $output = $this->writer($type, $delimit, $file, $filepath, 7);
                    break;
                case 'cotizaciones':
                    $output = $this->writer($type, $delimit, $file, $filepath, 2);
                    break;
                case 'comerciales':
                    $output = $this->writerCommercial($type, $file, $filepath);
                    break;
                case 'gestores':
                    $output = $this->writerGestor($type, $file, $filepath);
                    break;
                default:
                    break;
            }
        }

        return $output;
    }

    /**
     * Read the files
     * @param string $type
     * @param string $delimit
     * @param string $file
     * @param string $filepath
     * @param int    $columnIni
     *
     * @return string
     *
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function reader(string $type, string $delimit, string $file, string $filepath, int $columnIni): string
    {
        $blank     = 0;
        $iteration = 0;

        $reader = new CSV();
        $reader->setGlobalFunctionsHelper(new GlobalFunctionsHelper());
        $reader->setFieldDelimiter($delimit);
        $reader->open($filepath);
        $arrayToFail    = [];
        $duplicateEntry = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $iteration = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if (0 !== $iteration) {
                    if (!$row[$columnIni] || !is_numeric($row[$columnIni]) || $row[$columnIni] <= 0) {
                        $blank++;
                        array_push($arrayToFail, $iteration);
                    }
                }
                $iteration++;
            }
            break;
        }

        $total   = $iteration - 1;
        $failed  = $blank + count($duplicateEntry);
        $success = $total - $failed;

        $output = $this->out($file, $type, $total, $success, $failed, $arrayToFail, $duplicateEntry, $columnIni);

        return $output;
    }

    /**
     * Read commercials excel
     * @param string $type
     * @param string $file
     * @param string $filepath
     *
     * @return string
     *
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function readerCommercial(string $type, string $file, string $filepath): string
    {

        $reader          = ReaderFactory::create(Type::XLSX);
        $failedOch       = 0;
        $failedCr        = 0;
        $duplicateEntry  = [];
        $arrayInsertsOch = [];
        $arrayInsertsCr  = [];

        $reader->open($filepath);
        foreach ($reader->getSheetIterator() as $sheet) {
            $iteration = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if (0 !== $iteration) {
                    // CREAR ARRAYS DE INSERTS
                    if ($row[0] !== null || $row[1] !== null || $row[3] !== null) {
                        $arrayToInsert = [$row[0], $row[1], $row[3]];
                        $mustInsert    = true;
                        foreach ($arrayInsertsOch as $array) {
                            if ($array[0] === $arrayToInsert[0]
                                && $array[1] === $arrayToInsert[1]
                                && $array[2] === $arrayToInsert[2]
                            ) {
                                $mustInsert = false;
                            }
                            if (!($array[1] === $arrayToInsert[1] && $array[2] !== $arrayToInsert[2])) {
                                continue;
                            }
                            $mustInsert = false;
                            $failedOch++;
                            $iteration++;
                            array_push($arrayToInsert, $iteration);
                            array_push($duplicateEntry, $arrayToInsert);
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsOch, $arrayToInsert);
                        }
                    }
                    if ($row[1] !== null && $row[2] !== null) {
                        $arrayToInsert = [strtoupper($row[1]), strtoupper($row[2])];
                        $mustInsert    = true;
                        foreach ($arrayInsertsCr as $array) {
                            if ($array[0] !== $arrayToInsert[0]) {
                                continue;
                            }
                            $mustInsert = false;
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsCr, $arrayToInsert);
                        }
                    } else {
                        $failedCr++;
                    }
                }
                $iteration++;
            }
            break;
        }

        $countOch = count($arrayInsertsOch);
        $countCr  = count($arrayInsertsCr);

        $output = $this->outComerciales(
            $file,
            $type,
            $countOch,
            $countCr,
            $failedOch,
            $failedCr,
            $duplicateEntry
        );

        return $output;
    }

    /**
     * read gestors excel to preview
     * @param string $type
     * @param string $file
     * @param string $filepath
     *
     * @return string
     *
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function readerGestor(string $type, string $file, string $filepath): string
    {
        $reader          = ReaderFactory::create(Type::XLSX);
        $failedOch       = 0;
        $failedCr        = 0;
        $duplicateEntry  = [];
        $arrayInsertsOch = [];
        $arrayInsertsCr  = [];

        $reader->open($filepath);
        foreach ($reader->getSheetIterator() as $sheet) {
            $iteration = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if (0 !== $iteration) {
                    // CREAR ARRAYS DE INSERTS
                    if ($row[1] !== null || $row[3] !== null) {
                        $arrayToInsert = [$row[1], $row[3]];
                        $mustInsert    = true;
                        foreach ($arrayInsertsOch as $array) {
                            //NO INSERTAR LA MISMA FILA
                            if (!($array[0] === $arrayToInsert[0] && $array[1] === $arrayToInsert[1])) {
                                continue;
                            }
                            $mustInsert = false;
                            $failedOch++;
                            array_push($arrayToInsert, $iteration + 1);
                            array_push($duplicateEntry, $arrayToInsert);
                        }
                        //VALOR CORRECTO
                        if (true === $mustInsert) {
                            array_push($arrayInsertsOch, $arrayToInsert);
                        }
                    }

                    if ($row[1] !== null && $row[2] !== null) {
                        $arrayToInsert = [strtoupper($row[1]), strtoupper($row[2])];
                        $mustInsert    = true;
                        foreach ($arrayInsertsCr as $array) {
                            if ($array[0] !== $arrayToInsert[0]) {
                                continue;
                            }
                            $mustInsert = false;
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsCr, $arrayToInsert);
                        }
                    } else {
                        $failedCr++;
                    }
                }
                $iteration++;
            }
            break;
        }

        $countOch = count($arrayInsertsOch);
        $countCr  = count($arrayInsertsCr);

        $output = $this->outGestores(
            $file,
            $type,
            $countOch,
            $countCr,
            $failedOch,
            $failedCr,
            $duplicateEntry
        );

        return $output;
    }

    /**
     * show the messages to the preview
     * @param string $file
     * @param string $type
     * @param int    $total
     * @param int    $success
     * @param int    $failed
     * @param array  $arrayToFail
     * @param array  $duplicateEntry
     * @param int    $columnIni
     *
     * @return string
     */
    public function out(string $file, string $type, int $total, int $success, int $failed, array $arrayToFail, array $duplicateEntry, int $columnIni): string
    {
        $output  = 'File Name: '.$file.'<br>';
        $output .= 'Type: '.$type.'<br>';
        $output .= 'Total rows: '.$total.'<br>';
        $output .= 'Success rows: '.$success.'<br>';
        $output .= 'Failed rows: '.$failed.'<br>';

        if (count($arrayToFail) > 0 || count($duplicateEntry) > 0) {
            $output .= '<br><pre class="xdebug-var-dump" dir="ltr">';
            $output .= '<strong>Failed rows registry:</strong><br>';
        }

        if (count($arrayToFail) > 0) {
            foreach ($arrayToFail as $entry) {
                $output .= '<span style="color: red">Fatal error: </span><br>The row '.$entry.' has errors.<br>';
            }
        }

        if (count($duplicateEntry) > 0) {
            foreach ($duplicateEntry as $entryD) {
                $output .= '<span style="color: red">Fatal error: </span><br>The ID '.$entryD[$columnIni].' is duplicated.<br>';
            }
        }

        if (count($arrayToFail) > 0 || count($duplicateEntry) > 0) {
            $output .= '<br>Check the uploaded document and execute the command again. <br>';
        }
        $output .= '</pre><br>';

        $this->out = $output;

        return $output;
    }

    /**
     * show the message to commercials excel preview
     * @param string $file
     * @param string $type
     * @param int    $countOch
     * @param int    $countCr
     * @param int    $failedOch
     * @param int    $failedCr
     * @param array  $duplicateEntry
     *
     * @return string
     */
    public function outComerciales(string $file, string $type, int $countOch, int $countCr, int $failedOch, int $failedCr, array $duplicateEntry): string
    {
        $totalOch = $countOch + $failedOch;
        $totalCr  = $countCr + $failedCr;
        $output   = '<h4>File information: </h4>';
        $output  .= 'File Name: '.$file.'<br>';
        $output  .= 'Type: '.$type.'<br><br>';
        $output  .= '<h4>Oficinas - Comerciales - Horizontales</h4>';
        $output  .= 'Total rows: '.$totalOch.'<br>';
        $output  .= 'Success rows: '.$countOch.'<br>';
        $output  .= 'Failed rows: '.$failedOch.'<br>';
        $output  .= '<h4>Comerciales - Responsables</h4>';
        $output  .= 'Total rows: '.$totalCr.'<br>';
        $output  .= 'Success rows: '.$countCr.'<br>';
        $output  .= 'Failed rows: '.$failedCr.'<br>';

        if (count($duplicateEntry) > 0) {
            $output .= '<br><pre class="xdebug-var-dump" dir="ltr">';
            $output .= '<strong>Failed rows registry:</strong><br>';
            foreach ($duplicateEntry as $entry) {
                $output .= '<span style="color: red">Fatal error: </span><br>The comercial '.$entry[1]
                    .' got 2 or more zones / responsable assigned on line '.$entry[3]
                    .'. <br>Check the uploaded document and execute the command again. <br><br>';
            }
            $output .= '</pre><br>';
        }

        return $output;
    }

    /**
     * show the messages to gestors excel preview
     * @param string $file
     * @param string $type
     * @param int    $countOch
     * @param int    $countCr
     * @param int    $failedOch
     * @param int    $failedCr
     * @param array  $duplicateEntry
     *
     * @return string
     */
    public function outGestores(string $file, string $type, int $countOch, int $countCr, int $failedOch, int $failedCr, array $duplicateEntry): string
    {
        $totalOch = $countOch + $failedOch;
        $totalCr  = $countCr + $failedCr;
        $output   = '<h4>File information: </h4>';
        $output  .= 'File Name: '.$file.'<br>';
        $output  .= 'Type: '.$type.'<br><br>';
        $output  .= '<h4>Gestores - Horizontales</h4>';
        $output  .= 'Total rows: '.$totalOch.'<br>';
        $output  .= 'Success rows: '.$countOch.'<br>';
        $output  .= 'Failed rows: '.$failedOch.'<br>';
        $output  .= '<h4>Gestores - Responsables</h4>';
        $output  .= 'Total rows: '.$totalCr.'<br>';
        $output  .= 'Success rows: '.$countCr.'<br>';
        $output  .= 'Failed rows: '.$failedCr.'<br>';

        if (count($duplicateEntry) > 0) {
            $output .= '<br><pre class="xdebug-var-dump" dir="ltr">';
            $output .= '<strong>Failed rows registry:</strong><br>';
            foreach ($duplicateEntry as $entry) {
                $output .= '<span style="color: red">Fatal error: </span><br>The gestor '.$entry[1].
                    ' got 2 or more zones / responsable assigned on line '.$entry[3].
                    '. <br>Check the uploaded document and execute the command again. <br><br>';
            }
            $output .= '</pre><br>';
        }

        return $output;
    }

    /**
     * show the message to the log
     * @param string $file
     * @param string $type
     * @param int    $total
     * @param int    $success
     * @param int    $failed
     * @param array  $arrayToFail
     * @param array  $duplicateEntry
     * @param int    $columnIni
     */
    public function outputWrite(string $file, string $type, int $total, int $success, int $failed, array $arrayToFail, array $duplicateEntry, int $columnIni): void
    {
        if (!$this->logger) {
            /* @todo Return excpetion. */
            exit;
        }

        $this->logger->info('File upload report.');
        $this->logger->info('File name '.$file);
        $this->logger->info('Type: '.$type);
        $this->logger->info('Total rows: '.$total);
        $this->logger->info('Success rows: '.$success);
        if ($failed > 0) {
            $this->logger->error('Failed rows: '.$failed);
        }

        if (count($arrayToFail) > 0) {
            foreach ($arrayToFail as $entry) {
                $this->logger->error('The row: '.$entry.' has errors.');
            }
        }

        if (!count($duplicateEntry) > 0) {
            /* @todo Return excpetion. */
            return;
        }
        foreach ($duplicateEntry as $entryD) {
            $this->logger->error('The ID: '.$entryD[$columnIni].' is duplicated.');
        }
    }

    /**
     * add to database the commercial excel
     * @param string $type
     * @param string $file
     * @param string $filepath
     *
     * @return string
     *
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     * @throws DBALException
     */
    public function writerCommercial(string $type, string $file, string $filepath): string
    {
        $connection = $this->em->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('commercial_office', true));
        $connection->executeUpdate($platform->getTruncateTableSQL('commercial_responsable', true));

        $reader          = ReaderFactory::create(Type::XLSX);
        $failedOch       = 0;
        $failedCr        = 0;
        $duplicateEntry  = [];
        $arrayInsertsOch = [];
        $arrayInsertsCr  = [];

        $reader->open($filepath);
        foreach ($reader->getSheetIterator() as $sheet) {
            $iteration = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if (0 !== $iteration) {
                    // CREAR ARRAYS DE INSERTS
                    if ($row[0] !== null || $row[1] !== null || $row[3] !== null) {
                        $arrayToInsert = [$row[0], $row[1], $row[3]];
                        $mustInsert    = true;
                        foreach ($arrayInsertsOch as $array) {
                            if ($array[0] === $arrayToInsert[0]
                                && $array[1] === $arrayToInsert[1]
                                && $array[2] === $arrayToInsert[2]
                            ) {
                                $mustInsert = false;
                            }
                            if (!($array[1] === $arrayToInsert[1] && $array[2] !== $arrayToInsert[2])) {
                                continue;
                            }
                            $mustInsert = false;
                            $failedOch++;
                            array_push($arrayToInsert, $iteration + 1);
                            array_push($duplicateEntry, $arrayToInsert);
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsOch, $arrayToInsert);
                        }
                    }
                    if ($row[1] !== null && $row[2] !== null) {
                        $arrayToInsert = [strtoupper($row[1]), strtoupper($row[2])];
                        $mustInsert    = true;
                        foreach ($arrayInsertsCr as $array) {
                            if ($array[0] !== $arrayToInsert[0]) {
                                /* @todo Return excpetion. */
                                continue;
                            }
                            $mustInsert = false;
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsCr, $arrayToInsert);
                        }
                    } else {
                        $failedCr++;
                    }
                }
                $iteration++;
            }
            break;
        }
        // INSERTS PARA OFFICE - COMERCIAL - HORIZONTAL
        foreach ($arrayInsertsOch as $och) {
            $data = new CommercialOffice();

            //Formatear el codigo de oficina
            // Rellenando el campo con cero hasta tener 5 digitos
            $stringCode = $this->formatOfficeCode($och[0]);

            $data->setOffice((string) $stringCode);
            $data->setCommercial((string) $och[1]);
            $data->setHorizontal((string) $och[2]);
            $this->em->persist($data);
        }
        $this->em->flush();
        $this->em->clear();

        // INSERTS PARA COMERCIAL - RESPONSABLE
        foreach ($arrayInsertsCr as $cr) {
            $data2 = new CommercialResponsable();
            $data2->setCommercial((string) $cr[0]);
            $data2->setResponsable((string) $cr[1]);
            $this->em->persist($data2);
        }

        $this->em->flush();
        $this->em->clear();

        $countOch = count($arrayInsertsOch);
        $countCr  = count($arrayInsertsCr);

        $output = $this->outWriterComerciales(
            $file,
            $type,
            $countOch,
            $countCr,
            $failedOch,
            $failedCr,
            $duplicateEntry
        );

        return $output;
    }

    /**
     * add gestor excel to db
     * @param string $type
     * @param string $file
     * @param string $filepath
     *
     * @return string
     *
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     * @throws DBALException
     */
    public function writerGestor(string $type, string $file, string $filepath): string
    {
        $connection = $this->em->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('gestor_horizontal', true));
        $connection->executeUpdate($platform->getTruncateTableSQL('gestor_responsable', true));

        $reader          = ReaderFactory::create(Type::XLSX);
        $failedOch       = 0;
        $failedCr        = 0;
        $duplicateEntry  = [];
        $arrayInsertsOch = [];
        $arrayInsertsCr  = [];

        $reader->open($filepath);

        foreach ($reader->getSheetIterator() as $sheet) {
            $iteration = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if (0 !== $iteration) {
                    // CREAR ARRAYS DE INSERTS
                    if ($row[1] !== null || $row[3] !== null) {
                        $arrayToInsert = [$row[1], $row[3]];
                        $mustInsert    = true;
                        foreach ($arrayInsertsOch as $array) {
                            if (!($array[0] === $arrayToInsert[0] and $array[1] === $arrayToInsert[1])) {
                                continue;
                            }
                            $mustInsert = false;
                            $failedOch++;
                            array_push($arrayToInsert, $iteration + 1);
                            array_push($duplicateEntry, $arrayToInsert);
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsOch, $arrayToInsert);
                        }
                    }
                    if ($row[1] !== null && $row[2] !== null) {
                        $arrayToInsert = [strtoupper($row[1]), strtoupper($row[2])];
                        $mustInsert    = true;
                        foreach ($arrayInsertsCr as $array) {
                            if ($array[0] !== $arrayToInsert[0]) {
                                /* @todo Return excpetion. */
                                continue;
                            }
                            $mustInsert = false;
                        }
                        if (true === $mustInsert) {
                            array_push($arrayInsertsCr, $arrayToInsert);
                        }
                    } else {
                        $failedCr++;
                    }
                }
                $iteration++;
            }
            break;
        }

        // INSERTS PARA OFFICE - COMERCIAL - HORIZONTAL
        foreach ($arrayInsertsOch as $och) {
            $data = new GestorHorizontal();
            $data->setGestor($och[0]);
            $data->setHorizontal($och[1]);
            $this->em->persist($data);
        }
        $this->em->flush();
        $this->em->clear();

        // INSERTS PARA COMERCIAL - RESPONSABLE
        foreach ($arrayInsertsCr as $cr) {
            $data2 = new GestorResponsable();
            $data2->setGestor($cr[0]);
            $data2->setResponsable($cr[1]);
            $this->em->persist($data2);
        }
        $this->em->flush();
        $this->em->clear();

        $countOch = count($arrayInsertsOch);
        $countCr  = count($arrayInsertsCr);

        $output = $this->outWriterGestors(
            $file,
            $type,
            $countOch,
            $countCr,
            $failedOch,
            $failedCr,
            $duplicateEntry
        );

        return $output;
    }


    /**
     * @param string $type
     * @param string $delimit
     * @param string $file
     * @param string $filepath
     * @param int    $columnIni
     *
     * @return string
     *
     * @throws IOException
     * @throws ReaderNotOpenedException
     * @throws ConnectionException
     */
    public function writer(string $type, string $delimit, string $file, string $filepath, int $columnIni): ?string
    {
        $chunksize      = 100;
        $iterationChunk = 0;
        $total          = 0;
        $blank          = 0;
        $iteration      = 0;
        $connection     = $this->em->getConnection();

        $reader = new CSV();
        $reader->setGlobalFunctionsHelper(new GlobalFunctionsHelper());
        $reader->setFieldDelimiter($delimit);
        $reader->open($filepath);

        $arrayToFail    = [];
        $duplicateEntry = [];

        $table = null;

        // $stopwatch = new Stopwatch();
        // $stopwatch->start('eventName');

        switch ($type) {
            case 'oficinas':
                $table = 'maestra_oficina';
                break;
            case 'empleados':
                $table = 'maestra_empleados_cxb';
                break;
            case 'proveedores':
                $table = 'maestra_proveedor';
                break;
            case 'clientes':
                $table = 'maestra_cliente';
                break;
            case 'familia':
                $table = 'maestra_familia';
                break;
            case 'familiaSub':
                $table = 'maestra_subfamilia';
                break;
            case 'familiaRel':
                $table = 'maestra_familia_rel';
                break;
            case 'solicitudes':
                $table = 'maestra_solicitud';
                break;
            case 'cotizaciones':
                $table = 'maestra_cotizacion';
                break;
            default:
                $table = null;
                break;
        }

        try {
            //borrar tabla maestro
            $connection->executeUpdate($connection->getDatabasePlatform()->getTruncateTableSQL($table, true));
        } catch (Exception $e) {
            return $e->getMessage();
        }

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if (0 !== $iteration) {
                    // No existeix o no es correcte ClienteID
                    if (!$row[$columnIni] || !is_numeric($row[$columnIni]) || $row[$columnIni] <= 0) {
                        $blank++;

                        array_push($arrayToFail, $iteration);
                    } else {
                        try {
                            switch ($type) {
                                case 'oficinas':
                                    $data = new MasterOffice();
                                    break;
                                case 'empleados':
                                    $data = new MasterEmployee();
                                    break;
                                case 'proveedores':
                                    $data = new MasterProvider();
                                    break;
                                case 'clientes':
                                    $data = new MasterCustomer();
                                    break;
                                case 'familia':
                                    $data = new MasterFamily();
                                    break;
                                case 'familiaSub':
                                    $data = new MasterFamilySub();
                                    break;
                                case 'familiaRel':
                                    $data = new MasterFamilyRel();
                                    break;
                                case 'solicitudes':
                                    $data = new MasterRequest();
                                    break;
                                case 'cotizaciones':
                                    $data = new MasterQuotation();
                                    break;
                                default:
                                    $data = null;
                                    break;
                            }

                            $data->setData($row, $columnIni);
                            $this->em->persist($data);

                            /**
                             * CHUNKSIZE VERIFICATION
                             */
                            if ($chunksize === $iterationChunk) {
                                $this->em->flush();
                                $this->em->clear();
                                $iterationChunk = 0;
                            }
                            $iterationChunk++;
                        } catch (Exception $e) {
                            return  $e->getMessage();
                        }
                    }
                }
                $total++;
                $iteration++;
            }
            break;
        }

        try {
            $this->em->flush();
            $this->em->clear();

            $total   = $iteration - 1;
            $failed  = $blank + count($duplicateEntry);
            $success = $total - $failed;

            // $stopwatch->stop('eventName');

            $this->outputWrite($file, $type, $total, $success, $failed, $arrayToFail, $duplicateEntry, $columnIni);
        } catch (Exception $e) {
            return $e->getMessage();
        }


        return null;
    }

    /**
     * out message to log commerciales excel import
     * @param string $file
     * @param string $type
     * @param int    $countOch
     * @param int    $countCr
     * @param int    $failedOch
     * @param int    $failedCr
     * @param array  $duplicateEntry
     *
     * @return string
     */
    public function outWriterComerciales(string $file, string $type, int $countOch, int $countCr, int $failedOch, int $failedCr, array $duplicateEntry): string
    {
        $output  = '';
        $outputF = '';
        if ($this->logger) {
            $totalOch = $countOch + $failedOch;
            $totalCr  = $countCr + $failedCr;
            $output   = ' File information: ';
            $output  .= ' File Name: '.$file;
            $output  .= ' Type: '.$type;
            $output  .= ' Oficinas - Comerciales - Horizontales';
            $output  .= ' Total rows: '.$totalOch;
            $output  .= ' Success rows: '.$countOch;
            $output  .= ' Failed rows: '.$failedOch;
            $output  .= ' Comerciales - Responsables';
            $output  .= ' Total rows: '.$totalCr;
            $output  .= ' Success rows: '.$countCr;
            $output  .= ' Failed rows: '.$failedCr;
            $this->logger->info($output);


            if (count($duplicateEntry) > 0) {
                $outputF = 'Failed rows registry:';
                foreach ($duplicateEntry as $entry) {
                    $outputF .= ' The comercial '.$entry[1].' got 2 or more zones / responsable assigned on line '
                        .$entry[3].'. Check the uploaded document and execute the command again.';
                }
                $this->logger->error($outputF);
            }
        }

        $output .= " {$outputF}";

        return $output;
    }

    /**
     * out message to log gestors excel import
     * @param string $file
     * @param string $type
     * @param int    $countOch
     * @param int    $countCr
     * @param int    $failedOch
     * @param int    $failedCr
     * @param array  $duplicateEntry
     *
     * @return string
     */
    public function outWriterGestors(string $file, string $type, int $countOch, int $countCr, int $failedOch, int $failedCr, array $duplicateEntry): string
    {
        $output  = '';
        $outputF = '';
        if ($this->logger) {
            $totalOch = $countOch + $failedOch;
            $totalCr  = $countCr + $failedCr;
            $output   = ' File information: ';
            $output  .= ' File Name: '.$file;
            $output  .= ' Type: '.$type;
            $output  .= ' Gestores - Horizontales';
            $output  .= ' Total rows: '.$totalOch;
            $output  .= ' Success rows: '.$countOch;
            $output  .= ' Failed rows: '.$failedOch;
            $output  .= ' Gestores - Responsables';
            $output  .= ' Total rows: '.$totalCr;
            $output  .= ' Success rows: '.$countCr;
            $output  .= ' Failed rows: '.$failedCr;
            $this->logger->info($output);

            if (count($duplicateEntry) > 0) {
                $outputF = 'Failed rows registry:';
                foreach ($duplicateEntry as $entry) {
                    $outputF .= ' The gestor '.$entry[1].' got 2 or more zones / responsable assigned on line '.$entry[3].'. Check the uploaded document and execute the command again.';
                }
                $this->logger->error($outputF);
            }
        }

        $output .= " {$outputF}";

        return $output;
    }

    /**
     * Formatear el codigo de oficina
     * Rellenando el campo con cero hasta tener 5 digitos
     * @param string $officeCode
     *
     * @return string
     */
    public function formatOfficeCode(string $officeCode): string
    {
        $lenCode = strlen($officeCode);
        switch ($lenCode) {
            case 1:
                $stringCode = '0000'.$officeCode;
                break;
            case 2:
                $stringCode = '000'.$officeCode;
                break;
            case 3:
                $stringCode = '00'.$officeCode;
                break;
            case 4:
                $stringCode = '0'.$officeCode;
                break;
            default:
                $stringCode = $officeCode;
        }

        return $stringCode;
    }
}
