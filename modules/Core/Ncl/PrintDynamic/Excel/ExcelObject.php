<?php


namespace Modules\Core\Ncl\PrintDynamic\Excel;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Modules\Core\Ncl\FunctionHelper;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

class ExcelObject implements WithEvents
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
                // Set các giá trị khác
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
        $styleTable = array(
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'font' => [
                'name'      =>  'TimesNewRoman',
                'size'      =>  12,
            ]
        );
        $stt = 1;
        $iteration = $this->iteration;
        $iterationsSum = []; // tổng theo nhóm lĩnh vực nếu có
        $cateName = '';
        foreach ($this->dataTable as $key => $value) {
            if (array_key_exists('type', $value) && $value['type'] == $this->mergeRow['data_type']) {
                if ($this->mergeRow['type_cells'] == 'merge') {
                    $sheet->mergeCells($this->mergeRow['start_column'] . $iteration . ":" . $this->mergeRow['end_column'] . $iteration);
                    $sheet->setCellValue($this->mergeRow['start_column'] . $iteration, $value[$this->mergeRow['data_column']]);
                    $sheet->getDelegate()->getStyle($this->mergeRow['start_column'] . $iteration)->getFont()->setBold(true);
                    $sheet->getDelegate()->getStyle($this->mergeRow['start_column'] . $iteration)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $iteration++;
                    continue;
                }
                if ($this->mergeRow['type_cells'] == 'sum') {
                    $sheet->mergeCells($this->mergeRow['start_column'] . $iteration . ":" . $this->mergeRow['end_column'] . $iteration);
                    $sheet->setCellValue($this->mergeRow['start_column'] . $iteration, $value[$this->mergeRow['data_column']]);
                    $sheet->getDelegate()->getStyle($this->mergeRow['start_column'] . $iteration)->getFont()->setBold(true);
                    $sheet->getDelegate()->getStyle($this->mergeRow['start_column'] . $iteration)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $startRowSum = $iteration + 1;
                    $endRowSum = $iteration + $value['numberRecordtype'];
                    foreach ($this->mergeRow['column_sum'] as $columnSum) {
                        $sheet->setCellValue($columnSum . $iteration, '=SUM(' . $columnSum . $startRowSum . ':' . $columnSum . $endRowSum . ')');
                        $sheet->getDelegate()->getStyle($columnSum . $iteration)->getFont()->setBold(true);
                    }
                    $iterationsSum[] = $iteration++;
                    continue;
                }
            }
            
            foreach ($this->configTables as $configTable) {
                if (isset($configTable['group_name'])) {
                    if ($cateName != $value['sGroupCode']) {
                        if (isset($configTable['group_name'])) {
                            $sheet->mergeCells($this->configTables[0]['name_column'] . $iteration . ":" . (array_reverse($this->configTables))[0]['name_column'] . $iteration);
                            $sheet->setCellValue($this->configTables[0]['name_column'] . $iteration, $value[$configTable['group_name']]);
                            $sheet->getDelegate()->getStyle($this->configTables[0]['name_column'] . $iteration)->getFont()->setBold(true);
                            $sheet->getDelegate()->getStyle($this->configTables[0]['name_column'] . $iteration)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                            ++$iteration;
                        }
                        $cateName = $value['sGroupCode'];
                    }
                }
                if ($configTable['data_column'] == 'stt') {
                    $sheet->setCellValue($configTable['name_column'] . ($iteration), $stt++);
                    continue;
                }
                if ($value[$configTable['data_column']] != null && $configTable['from_xml_data'] == 'false') {
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
                if ($value[$configTable['data_column']] != null && $configTable['from_xml_data'] == 'true') {
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
        if (!empty($this->configReFooter) && empty($iterationsSum)) {
            foreach ($this->configReFooter as $config) {
                if (!empty($config['mergeColumn'])) {
                    $columns = explode(',', $config['mergeColumn']);
                    for ($i = 0; $i < count($columns); $i++) {
                        $sheet->mergeCells($columns[$i] . $iteration . ":" . array_pop($columns) . $iteration);
                        $sheet->setCellValue($config['mergeName'] . $iteration, 'Tổng');
                        $sheet->getDelegate()->getStyle($config['mergeName'] . $iteration)->getFont()->setBold(true);
                    }
                }
                if (!empty($config['name_column'])) {
                    $sheet->setCellValue($config['name_column'] . $iteration, '=SUM(' . $config['name_column'] . $this->iteration . ':' . $config['name_column'] . --$iteration . ')');
                    $sheet->getDelegate()->getStyle($config['name_column'] . ++$iteration)->getFont()->setBold(true);
                }
            }
        } elseif (!empty($this->configReFooter) && !empty($iterationsSum)) {
            foreach ($this->configReFooter as $config) {
                if (!empty($config['mergeColumn'])) {
                    $columns = explode(',', $config['mergeColumn']);
                    for ($i = 0; $i < count($columns); $i++) {
                        $sheet->mergeCells($columns[$i] . $iteration . ":" . array_pop($columns) . $iteration);
                        $sheet->setCellValue($config['mergeName'] . $iteration, 'Tổng');
                        $sheet->getDelegate()->getStyle($config['mergeName'] . $iteration)->getFont()->setBold(true);
                    }
                }
                if (!empty($config['name_column'])) {
                    $stringCellsSum = '';
                    foreach ($iterationsSum as $iterationSum) {
                        $stringCellsSum .= $config['name_column'] . $iterationSum . ',';
                    }
                    $sheet->setCellValue($config['name_column'] . $iteration, '=SUM(' . $stringCellsSum . ')');
                    $sheet->getDelegate()->getStyle($config['name_column'] . $iteration)->getFont()->setBold(true);
                }
            }
        }
    }
}
