<?php

namespace app\widgets;

use yii\base\Component;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;

class TableGenerator extends Component
{
    public static function table($model, $data_in = [], $provider = null) {
        // Get attributes from the model, excluding 'actions'
        $attributes = $model->tableColumns();
        $data = $data_in;

        // Check if actions are defined
        $hasActions = false;
        $actionColumn = null;
        foreach ($attributes as $key => $headerInfo) {
            if (is_array($headerInfo) && isset($headerInfo['actions'])) {
                $hasActions = true;
                $actionColumn = [
                    'key' => $key,
                    'actions' => $headerInfo['actions']
                ];
                unset($attributes[$key]);
                break;
            }
        }

        // Start table HTML
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-hover mb-3">';

        // Table header
        $html .= '<thead class="thead-dark">';
        $html .= '<tr>';

        // Add row number column header as the first column
        $html .= '<th>No</th>';

        // Render standard column headers
        foreach ($attributes as $key => $headerInfo) {
            $header = is_array($headerInfo) ? $headerInfo['label'] : $headerInfo;
            $html .= '<th>' . Html::encode($header) . '</th>';
        }

        // Add actions column header if actions exist
        if ($hasActions) {
            $html .= '<th>Actions</th>';
        }

        $html .= '</tr></thead>';

        // Table body
        $html .= '<tbody>';
        foreach ($data as $index => $row) {
            $html .= '<tr>';

            // Add row number as the first column
            $html .= '<td>' . ($index + 1) . '</td>';

            // Render standard columns
            foreach ($attributes as $key => $header) {
                if (is_array($header) && isset($header['format']) && is_callable($header['format'])) {
                    $value = $header['format']($row[$key]);
                } else {
                    $value = $row[$key];
                }
                $html .= in_array($key,['is_active','locked','active','status','archived'])  ? '<td>' . $value . '</td>' : '<td>' . Html::encode($value) . '</td>';
            }

            // Render actions column if actions exist
            if ($hasActions && $actionColumn) {
                $html .= '<td>';
                foreach ($actionColumn['actions'] as $action) {
                    if (is_callable($action)) {
                        $html .= $action($row) . ' ';
                    }
                }
                $html .= '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        // Add pagination if provider is passed
        if ($provider !== null) {
            $html .= '<div class="mt-3">';
            // Start output buffering to capture LinkPager widget
            ob_start();
            echo LinkPager::widget(['pagination' => $provider->pagination]);
            $paginationHtml = ob_get_clean();
            $html .= $paginationHtml;
            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;
    }
}
