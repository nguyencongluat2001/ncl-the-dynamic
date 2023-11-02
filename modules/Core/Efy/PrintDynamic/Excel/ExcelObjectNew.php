<?php


namespace Modules\Core\Efy\PrintDynamic\Excel;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Modules\Core\Efy\FunctionHelper;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

class ExcelObjectNew implements WithEvents
{
    public $dataTable;
    public $fileTemplate;
    public $arrDataCells;
    public $iteration;
    public $configTable;
    public $onceDataCells;
    public $mergeRow;
    private $configTables;
    private $configReFooter;

    function __construct($dataTable, $configReplace, $configFooter, $fileTemplate)
    {
        $this->fileTemplate   = $fileTemplate;
        $this->dataTable      = $dataTable;
        $this->iteration      = $configReplace['iteration'];
        $this->configTables   = $configReplace['configTableCells'];
        $this->onceDataCells  = $configReplace['configCells'];
        $this->mergeRow       = $configReplace['configTableMergeRow'];
        $this->configReFooter = $configFooter['configTableCells'];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                // Load file template
                $event->writer->reopen(new LocalTemporaryFile($this->fileTemplate), Excel::XLSX);
                // Get sheet 0
                $sheet = $event->writer->getSheetByIndex(0);
                // // Set các giá trị khác
                $this->setOnceData($sheet); // $event->getWriter()->getSheetByIndex(0)
                // Set các giá trị dữ liệu trong bảng
                $this->populateSheet($sheet);
                // Call the export on the first sheet
                $event->writer->getSheetByIndex(0)->export($event->getConcernable());

                return $event->getWriter()->getSheetByIndex(0);
            }
        ];
    }

    /**
     * Set các giá trị khác ngoài các row data (thời gian, địa điểm, ...)
     * 
     * @param object $sheet
     */
    private function setOnceData($sheet)
    {
        foreach ($this->onceDataCells as $key => $value) {
            $sheet->setCellValue($key, $value);
        }
    }

    /**
     * Set các giá trị vào các row
     * 
     * @param object $sheet
     */
    private function populateSheet($sheet)
    {
        $stt = 1;
        $iteration = $this->iteration;
        $cateName = '';
        $data = is_array($this->dataTable) ? $this->dataTable : $this->dataTable->toArray();
        foreach ($data as $key => $value) {
            if (array_key_exists('type', $value) && $value['type'] == $this->mergeRow['data_type']) {
                $sheet->mergeCells($this->mergeRow['start_column'] . $iteration . ':' . $this->mergeRow['end_column'] . $iteration);
                $sheet->setCellValue($this->mergeRow['start_column'] . $iteration, $value[$this->mergeRow['data_column']]);
                $sheet->getDelegate()->getStyle($this->mergeRow['start_column'] . $iteration)->getFont()->setBold(true);
                $sheet->getDelegate()->getStyle($this->mergeRow['start_column'] . $iteration)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $iteration++;
                continue;
            }
            foreach ($this->configTables as $configTable) {
                if (isset($configTable['group_name'])) {
                    if ($cateName != $value['sGroupCode']) {
                        if (isset($configTable['group_name'])) {
                            $sheet->mergeCells($this->configTables[0]['name_column'] . $iteration . ':' . (array_reverse($this->configTables))[0]['name_column'] . $iteration);
                            $sheet->setCellValue($this->configTables[0]['name_column'] . $iteration, $value[$configTable['group_name']]);
                            $sheet->getDelegate()->getStyle($this->configTables[0]['name_column'] . $iteration)->getFont()->setBold(true);
                            $sheet->getDelegate()->getStyle($this->configTables[0]['name_column'] . $iteration)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                            ++$iteration;
                        }
                        $cateName = $value['sGroupCode'];
                    }
                }
                if (empty($configTable['data_column'])) continue;
                if ($configTable['data_column'] == 'stt') {
                    $sheet->setCellValue($configTable['name_column'] . ($iteration), $stt++);
                    continue;
                }
                if (
                    !isset($value[$configTable['data_column']]) ||
                    (string)$value[$configTable['data_column']] == ''
                ) continue;
                if ($configTable['from_xml_data'] == 'false') {
                    if (!empty($configTable['phpfunction'])) {
                        $phpfunction = $configTable['phpfunction'];
                        $sheet->setCellValue(
                            $configTable['name_column'] . ($iteration),
                            FunctionHelper::$phpfunction($value[$configTable['data_column']])
                        );
                    } else {
                        $sheet->setCellValue($configTable['name_column'] . ($iteration), $value[$configTable['data_column']]);
                    }
                    continue;
                }
                if ($configTable['from_xml_data'] == 'true') {
                    $xml_data = $value[$configTable['data_column']];
                    $dataInsert = FunctionHelper::_xmlGetXmlTagValue($xml_data, 'data_list', $configTable['tag_value']);
                    if (!empty($configTable['phpfunction'])) {
                        $phpfunction = $configTable['phpfunction'];
                        $dataInsert = FunctionHelper::$phpfunction($dataInsert);
                    }
                    $sheet->setCellValue($configTable['name_column'] . ($iteration), $dataInsert);
                    continue;
                }
            }
            $iteration++;
        }
        $this->setDataStyle($sheet, $iteration);
        if (!empty($this->configReFooter)) {
            foreach ($this->configReFooter as $config) {
                $this->setConfigReFooter($sheet, $config, $iteration);
            }
        }
    }

    /**
     * Set style các row dữ liệu
     * 
     * @param object $sheet
     * @param array $config
     * @param int $iteration
     */
    private function setDataStyle($sheet, $iteration)
    {
        $styleTable = array(
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
            // 'font' => [
            //     'name' => 'TimesNewRoman',
            //     'size' => 12,
            // ]
        );
        $beginIteration = $this->iteration;
        $arrColumn = array_map(function ($v) {
            return $v['name_column'];
        }, $this->configTables);
        sort($arrColumn);
        $sheet->getDelegate()
            ->getStyle($arrColumn[0] . $beginIteration . ':' . end($arrColumn) . ((int)$iteration - 1))
            ->applyFromArray($styleTable);
    }

    /**
     * Set dòng tổng số
     * 
     * @param object $sheet
     * @param array $config
     * @param int $iteration
     */
    private function setConfigReFooter($sheet, $config, $iteration)
    {
        if (!empty($config['mergeColumn'])) {
            $columns = explode(',', $config['mergeColumn']);
            for ($i = 0; $i < count($columns); $i++) {
                $sheet->mergeCells($columns[$i] . $iteration . ':' . array_pop($columns) . $iteration);
                $sheet->setCellValue($config['mergeName'] . $iteration, 'Tổng');
                $sheet->getDelegate()->getStyle($config['mergeName'] . $iteration)->getFont()->setBold(true);
            }
        }
        if (!empty($config['name_column'])) {
            $sheet->setCellValue($config['name_column'] . $iteration, '=SUM(' . $config['name_column'] . $this->iteration . ':' . $config['name_column'] . --$iteration . ')');
            $sheet->getDelegate()->getStyle($config['name_column'] . ++$iteration)->getFont()->setBold(true);
        }
        if ($config['from_xml_data'] == 'false') {
            if (!empty($config['phpfunction'])) {
                $phpfunction = $config['phpfunction'];
                $sheet->setCellValue(
                    $config['name_column'] . ($iteration),
                    FunctionHelper::$phpfunction($config['data_column'], $iteration)
                );
            }
        }
    }
}
