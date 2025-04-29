<?php

namespace app\components;

use Exception;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use yii\base\Component;

class ExcelPdfGenerator extends Component
{

    public static function generatePdf($model, $options = [])
    {
        try {
            // Create a new mPDF instance with custom settings
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L', // Landscape format
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 25,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);

            // Set header and footer if provided
            if (isset($options['header'])) {
                $mpdf->SetHeader($options['header']);
            }
            // Set footer with page number if provided
            $footerText = isset($options['footer']) ? $options['footer'] : 'Page {PAGENO}';
            $mpdf->SetFooter($footerText);

            // Building HTML content for the PDF
            $html = '<html><head>';
            $html .= '<style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                background-color: #fff;
            }
            th, td {
                padding: 12px;
                text-align: left;
                border: 1px solid #ddd;
            }
            th {
                background-color: #000;
                color: white;
                font-weight: bold;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            tr:hover {
                background-color: #ddd;
            }
            .report-title {
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 20px;
                color: #333;
            }
            .report-subtitle {
                text-align: center;
                font-size: 14px;
                margin-bottom: 15px;
                color: #666;
            }
        </style>';
            $html .= '</head><body>';

            // Add title and subtitle if provided
            if (isset($options['title'])) {
                $html .= '<div class="report-title">' . htmlspecialchars($options['title']) . '</div>';
            }
            if (isset($options['subtitle'])) {
                $html .= '<div class="report-subtitle">' . htmlspecialchars($options['subtitle']) . '</div>';
            }

            // Get model attributes and data
            // Assuming the model is filtered with the search parameters
            $attributes = $model->exportColumns();
            $data = $model->getData(); // This should return filtered data

            // Create table
            $html .= '<table>';

            $html .= '<tr>';
            foreach ($attributes as $key => $headerInfo) {
                $header = is_array($headerInfo) ? $headerInfo['label'] : $headerInfo;
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr>';

            // Data rows
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ($attributes as $key => $header) {
                    if (is_array($header) && isset($header['format']) && is_callable($header['format'])) {
                        $value = $header['format']($row[$key]);
                    } else {
                        $value = $row[$key];
                    }
                    $html .= '<td style="text-transform:capitalize;">' . htmlspecialchars($value) . '</td>';
                    //  $html .= '<td style="text-transform:capitalize;">' . htmlspecialchars($row[$key]) . '</td>';
                }
                $html .= '</tr>';
            }

            $html .= '</table>';

            $html .= '</body></html>';

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Output PDF with dynamic filename
            $filename = (isset($options['filename']) ? $options['filename'] : 'export') . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $mpdf->Output($filename, 'D');

        } catch (Exception $e) {
            throw new Exception('Error generating PDF: ' . $e->getMessage());
        }
    }
}