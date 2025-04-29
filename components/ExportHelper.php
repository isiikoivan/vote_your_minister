<?php

namespace app\components;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Component;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportHelper extends Component
{

    public static function exportExcel($models, $filename = 'export')
    {
        $columns = is_array($models) ? $models[0]->exportColumns() : $models->exportColumns();
        $data = is_array($models) ? $models[0]->getData() : $models->getData();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        self::setHeaders($sheet, $columns);
        self::setData($sheet, $data, $columns);
        self::styleHeader($sheet, self::getLastColumn($columns));
        self::outputFile($spreadsheet, $filename);
    }

    private static function setHeaders($sheet, $columns)
    {
        $col = 'A';
        foreach ($columns as $field => $config) {
            $header = is_array($config) ? $config['label'] : $config;
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
    }

    private static function setData($sheet, $data, $columns)
    {
        $row = 2;
        foreach ($data as $model) {
            $col = 'A';
            foreach ($columns as $field => $config) {
                $value = self::getFieldValue($model, $field, $config);
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
    }

    private static function getFieldValue($model, $field, $config)
    {
        if (is_array($config) && isset($config['value'])) {
            $value = $config['value']($model);
        } else {
            $fieldValue = is_array($model) ? $model[$field] : $model->$field;
            $value = is_callable($field) ? $field($model) : $fieldValue;
        }
        if (is_array($config) && isset($config['format'])) {
            if (is_callable($config['format'])) {
                $value = $config['format']($value);
            } else {
                $value = sprintf($config['format'], $value);
            }
        }
        return $value;
    }

    private static function styleHeader($sheet, $lastColumn)
    {
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E2E2']
            ]
        ]);
    }

    private static function getLastColumn($columns)
    {
        return chr(ord('A') + count($columns) - 1);
    }

    private static function outputFile($spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }


}