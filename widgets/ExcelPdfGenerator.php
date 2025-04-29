<?php
namespace app\widgets;

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Component;

class ExcelPdfGenerator extends Component
{
    /**
     * Generate PDF from a model's data
     *
     * @param $model - The model containing data to be exported
     * @param array $options - Options for customizations such as title, header, footer, etc.
     * @throws \Exception
     */

//    public static function generatePdf($model, $options = [])
//    {
//        try {
//            // Create a new mPDF instance with custom settings
//            $mpdf = new Mpdf([
//                'mode' => 'utf-8',
//                'format' => 'A4-L', // Landscape format
//                'margin_left' => 10,
//                'margin_right' => 10,
//                'margin_top' => 25,
//                'margin_bottom' => 25,
//                'margin_header' => 10,
//                'margin_footer' => 10
//            ]);
//
//            // Set header and footer if provided
//            if (isset($options['header'])) {
//                $mpdf->SetHeader($options['header']);
//            }
//            // Set footer with page number if provided
//            $footerText = isset($options['footer']) ? $options['footer'] : 'Page {PAGENO}';
//            $mpdf->SetFooter($footerText);
//
//            // Building HTML content for the PDF
//            $html = '<html><head>';
//            $html .= '<style>
//            body {
//                font-family: Arial, sans-serif;
//                font-size: 12px;
//            }
//            table {
//                width: 100%;
//                border-collapse: collapse;
//                margin: 15px 0;
//                background-color: #fff;
//            }
//            th, td {
//                padding: 12px;
//                text-align: left;
//                border: 1px solid #ddd;
//            }
//            th {
//                background-color: #000;
//                color: white;
//                font-weight: bold;
//            }
//            tr:nth-child(even) {
//                background-color: #f2f2f2;
//            }
//            tr:hover {
//                background-color: #ddd;
//            }
//            .report-title {
//                text-align: center;
//                font-size: 18px;
//                font-weight: bold;
//                margin-bottom: 20px;
//                color: #333;
//            }
//            .report-subtitle {
//                text-align: center;
//                font-size: 14px;
//                margin-bottom: 15px;
//                color: #666;
//            }
//        </style>';
//            $html .= '</head><body>';
//
//            // Add title and subtitle if provided
//            if (isset($options['title'])) {
//                $html .= '<div class="report-title">' . htmlspecialchars($options['title']) . '</div>';
//            }
//            if (isset($options['subtitle'])) {
//                $html .= '<div class="report-subtitle">' . htmlspecialchars($options['subtitle']) . '</div>';
//            }
//
//            // Get model attributes and data
//            // Assuming the model is filtered with the search parameters
//            $attributes = $model->exportColumns();
//            $data = $model->getData(); // This should return filtered data
//
//            // Create table
//            $html .= '<table>';
//
//            $html .= '<tr>';
//            foreach ($attributes as $key => $headerInfo) {
//                $header = is_array($headerInfo) ? $headerInfo['label'] : $headerInfo;
//                $html .= '<th>' . htmlspecialchars($header) . '</th>';
//            }
//            $html .= '</tr>';
//
//            // Data rows
//            foreach ($data as $row) {
//                $html .= '<tr>';
//                foreach ($attributes as $key => $header) {
//                    if (is_array($header) && isset($header['format']) && is_callable($header['format'])) {
//                        $value = $header['format']($row[$key]);
//                    } else {
//                        $value = $row[$key];
//                    }
//                    $html .= '<td style="text-transform:capitalize;">' . htmlspecialchars($value) . '</td>';
//                    //  $html .= '<td style="text-transform:capitalize;">' . htmlspecialchars($row[$key]) . '</td>';
//                }
//                $html .= '</tr>';
//            }
//
//            $html .= '</table>';
//
//            $html .= '</body></html>';
//
//            // Write HTML to PDF
//            $mpdf->WriteHTML($html);
//
//            // Output PDF with dynamic filename
//            $filename = (isset($options['filename']) ? $options['filename'] : 'export') . '_' . date('Y-m-d_H-i-s') . '.pdf';
//            $mpdf->Output($filename, 'D');
//
//        } catch (\Exception $e) {
//            throw new \Exception('Error generating PDF: ' . $e->getMessage());
//        }
//    }



    private static function setHeaders($sheet, $columns)
    {
        $col = 'A';
        foreach ($columns as $field => $config) {
            $header = is_array($config) ? $config['header'] : $config;
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
    }

    private static function setData($sheet, $data, $columns)
    {

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
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
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
