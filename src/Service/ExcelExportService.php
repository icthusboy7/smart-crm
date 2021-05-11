<?php

namespace App\Service;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Contracts\Translation\TranslatorInterface;

class ExcelExportService
{
    public const TMPFOLDER = 'tmpexcel/';
    public const MIMETYPES = [
        'xls'  => 'application/msexcel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'xlsm' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
    ];

    /**
     * @var TranslatorInterface
     */
    public $translate;

    /**
     * Entity manager
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * ExcelExportService constructor.
     * @param TranslatorInterface    $translator
     * @param EntityManagerInterface $em
     */
    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em)
    {
        $this->translate = $translator;
        $this->em        = $em;
    }

    /**
     * @param array $pipelines
     * @param User  $currentUser
     *
     * @return string
     *
     * @throws Exception
     */
    public function createNewDocumentPipelines(array $pipelines, User $currentUser): string
    {

        $document = new Spreadsheet();

        $document->getProperties()
            ->setCreator('S.M.A.R.T.')
            ->setLastModifiedBy('S.M.A.R.T.')
            ->setTitle('Exportación')
            ->setSubject('Pipelines')
            ->setDescription('Exportación de pipelines');

        $document->setActiveSheetIndex(0)->setTitle('Export Pipelines');
        $sheet = $document->getActiveSheet();

        $sheet->setCellValue('A1', $this->translate->trans('Name'))
            ->setCellValue('B1', $this->translate->trans('Mes de activación'))
            ->setCellValue('C1', $this->translate->trans('Oficina'))
            ->setCellValue('D1', $this->translate->trans('Importe'))
            ->setCellValue('E1', $this->translate->trans('Tipo resultante'))
            ->setCellValue('F1', $this->translate->trans('Zona'))
            ->setCellValue('G1', $this->translate->trans('Vertical'))
            ->setCellValue('H1', $this->translate->trans('Tipo de Producto'))
            ->setCellValue('I1', $this->translate->trans('Tipo de Canal'))
            ->setCellValue('J1', $this->translate->trans('% de prob'))
            ->setCellValue('K1', $this->translate->trans('Es una línea?'))
            ->setCellValue('L1', $this->translate->trans('Responsables'))
            ->setCellValue('M1', $this->translate->trans('intervinientes'))
            ->setCellValue('N1', $this->translate->trans('Estado'))
            ->setCellValue('O1', $this->translate->trans('Excluir de los reportes?'))
            ->setCellValue('P1', $this->translate->trans('Información de la línea'))
            ->setCellValue('Q1', $this->translate->trans('Cotizaciones del expediente'))
            ->setCellValue('R1', $this->translate->trans('Disposiciones del expediente'));

        $sheet->getStyle('A1:R1')->getFont()->setBold(true);

        $i = 2;
        foreach ($pipelines as $pipeline) {
            $clientData   = $this->em->getRepository(MasterCustomer::class)
                ->findOneBy(['nif' => $pipeline->getClienteNif()]);
            $providerData = $this->em->getRepository(MasterProvider::class)
                ->findOneBy(['nif' => $pipeline->getPrescriptorCIF()]);
            $officeData   = $this->em->getRepository(MasterOffice::class)
                ->findOneBy(['codigo' => $pipeline->getOficina()]);

            /* Data Commercial */
            $commercialUser = $this->translate->trans('R.Commercial:')
                .' ('.$pipeline->getResponsable()->getRegNumber().')'
                .$pipeline->getResponsable()->getName();

            /* Data intervening Users */
            $interveningUser  = (!is_null($clientData) && !is_null($clientData->getNombre())
                ? $this->translate->trans('Client:').' '.$clientData->getNombre().' '
                : ''
            );
            $interveningUser .= (!is_null($providerData) && !is_null($providerData->getNombre())
                ? $this->translate->trans('Provider:').' '.$providerData->getNombre().' '
                : ''
            );
            $interveningUser .= (!is_null($officeData) && !is_null($officeData->getNombre())
                ? ' '.$this->translate->trans('Office:').' '.$officeData->getNombre().' '
                : ''
            );

            /* Get Info quotes of pipeline */
            $quotesIds = [];
            foreach ($pipeline->getCotizaciones() as $coti) {
                array_push($quotesIds, $coti->getCotizacion());
            }
            $quotes = $this->em->getRepository(ComercialCotizacion::class)
                ->findBy(
                    [
                        'numCoti' => $quotesIds,
                    ]
                );

            $j          = 1;
            $infoQuotes = '';
            foreach ($quotes as $quote) {
                $infoQuotes .= $this->translate->trans('Quote ').$j.' - ';
                $infoQuotes .= $quote->getNumCoti()
                    .' '.$quote->getEstado()
                    .' '.$quote->getCuota()
                    .' '.$quote->getPlazo().' ';
                $j++;
            }

            /* Get Info Provision of pipeline */
            $provisions = $this->em->getRepository(ComercialExpediente::class)
                ->findBy(
                    [
                        'esDisposicion'   => 1,
                        'expedientePadre' => $pipeline,
                    ]
                );

            $infoProvision = '';
            $j             = 1;
            foreach ($provisions as $provision) {
                $infoProvision .= $this->translate->trans('Provision ').$j.' - ';
                $infoProvision .= $provision->getTitulo()
                    .' '.$provision->getFechaPosibleActivacion()
                    .' '.$pipeline->getImporte()
                    .' '.$pipeline->getTin().' ';
                $j++;
            }

            $sheet->setCellValue('A'.$i, $pipeline->getTitulo())
                ->setCellValue('B'.$i, $pipeline->getFechaPosibleActivacion())
                ->setCellValue('C'.$i, $pipeline->getOficina())
                ->setCellValue('D'.$i, $pipeline->getImporte())
                ->setCellValue('E'.$i, $pipeline->getTin())
                ->setCellValue('F'.$i, $pipeline->getOficinaZona())
                ->setCellValue('G'.$i, $pipeline->getVertical())
                ->setCellValue('H'.$i, (!is_null($pipeline->getProductoID()) ? $pipeline->getProductoID()->getNombre() : ''))
                ->setCellValue('I'.$i, (!is_null($pipeline->getCanal()) ? $pipeline->getCanal()->getCanalDesc() : ''))
                ->setCellValue('J'.$i, $pipeline->getPorcentajeProbabilidad())
                ->setCellValue('K'.$i, ($pipeline->getEslinea() ? $this->translate->trans('YES') : $this->translate->trans('NO')))
                ->setCellValue('L'.$i, $commercialUser)
                ->setCellValue('M'.$i, $interveningUser)
                ->setCellValue('N'.$i, $pipeline->getStatus()->getStatus())
                ->setCellValue('O'.$i, ($pipeline->getNoReport() === 0 ? $this->translate->trans('NO') : $this->translate->trans('YES')))
                ->setCellValue('P'.$i, (!$pipeline->getEslinea() ? $this->translate->trans('NO') : $this->translate->trans('YES')))
                ->setCellValue('Q'.$i, $infoQuotes)
                ->setCellValue('R'.$i, $infoProvision);

            $i++;
        }

        $write = new Xlsx($document);

        $filename   = $currentUser->getRegNumber().'.xlsx';
        $fileToSave = '../'.getenv('FILES_UPLOAD').self::TMPFOLDER.$filename;

        try {
            $write->save($fileToSave);

            return $filename;
        } catch (\Exception $e) {
            return '';
        }
    }
}
