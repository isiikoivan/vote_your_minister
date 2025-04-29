<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class PageHeaderWidget extends Widget
{

    public $page = 'Page Title';

    public $path = '';

    public $button = 'Add New';

    public $export_model = null;

    public $additional_buttons = [];

    public $searchFields;

    public $model;

    public $containerOptions = ['class' => 'mb-5'];

    public $titleContainerOptions = ['class' => 'row mb-3'];

    public $buttonsContainerOptions = ['class' => 'row mb-5'];

    public $buttonsInnerOptions = ['class' => 'col-12 d-flex justify-content-end align-items-center border-bottom border-bottom-3 border-dark-subtle pb-3 gap-2'];

    public $mainButtonOptions = ['class' => 'btn btn-primary'];


    public $exportButtonOptions = ['class' => 'btn btn-danger dropdown-toggle'];

    protected function safeGetClass($object)
    {
        if (is_string($object)) {
            return $object;
        }
        return is_object($object) ? get_class($object) : '';
    }

    protected function renderButton($config)
    {
        if (!is_array($config)) {
            return '';
        }

        $label = $config['label'] ?? '';
        $url = $config['url'] ?? '#';
        $options = $config['options'] ?? [];
        $icon = $config['icon'] ?? '';

        // Merge default classes with provided options
        $defaultOptions = [
            'class' => 'btn',
        ];

        if (is_array($options)) {
            $options = array_merge($defaultOptions, $options);
        }

        // Ensure there's always a btn-* class
        if (!preg_match('/btn-(primary|secondary|success|danger|warning|info|light|dark)/', $options['class'])) {
            $options['class'] .= ' btn-secondary';
        }

        $buttonContent = '';
        if ($icon) {
            $buttonContent .= "<i class=\"{$icon} me-1\"></i>";
        }
        $buttonContent .= Html::encode($label);

        return Html::a($buttonContent, $url, $options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $html = '';

        // Title section
        $html .= Html::beginTag('div', $this->titleContainerOptions);
        $html .= Html::beginTag('div', ['class' => 'col-12']);
        $html .= Html::tag('h2', Html::encode($this->page));
        $html .= Html::endTag('div');
        $html .= Html::endTag('div');

        // Buttons section
        $html .= Html::beginTag('div', $this->buttonsContainerOptions);
        $html .= Html::beginTag('div', $this->buttonsInnerOptions);

        // Search fields (if provided)
        $html .= Html::beginTag('div', ['class' => 'me-auto']);
        if (isset($this->searchFields) && isset($this->model)) {
            $html .= $this->getView()->render('_search_fields', [
                'searchFields' => $this->searchFields,
                'model' => $this->model
            ]);
        }
        $html .= Html::endTag('div');

        // Export dropdown (if model provided)
        if ($this->export_model !== null) {
            $html .= $this->renderExportDropdown();
        }

        // Main action button (if path provided)
        if ($this->path !== '') {
            $html .= Html::a(
                Html::encode($this->button),
                Url::to([$this->path]),
                array_merge(
                    $this->mainButtonOptions,
                    ['aria-label' => Html::encode($this->button)]
                )
            );
        }

        // Additional buttons
        if (!empty($this->additional_buttons)) {
            if (isset($this->additional_buttons['label'])) {
                // Single button configuration
                $html .= $this->renderButton($this->additional_buttons);
            } else {
                // Multiple buttons
                foreach ($this->additional_buttons as $additionalButton) {
                    $html .= $this->renderButton($additionalButton);
                }
            }
        }

        $html .= Html::endTag('div');
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * Renders the export dropdown
     *
     * @return string The rendered export dropdown HTML
     */
    protected function renderExportDropdown()
    {
        $html = Html::beginTag('div', ['class' => 'dropdown']);

        // Dropdown toggle button with all attributes passed through
        $html .= Html::button(
            '<i class="ri-article-fill"></i> <span>Export</span>',
            array_merge(
                $this->exportButtonOptions,
                [
                    'type' => 'button',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => 'false'
                ]
            )
        );

        // Dropdown menu
        $html .= Html::beginTag('ul', ['class' => 'dropdown-menu']);

        // Excel export option
        $html .= Html::beginTag('li');
        $html .= Html::a(
            '<i class="ri-file-excel-2-line"></i> As Excel',
            Url::to(['excel-export', 'model' => $this->safeGetClass($this->export_model)]),
            ['class' => 'dropdown-item']
        );
        $html .= Html::endTag('li');

        // PDF export option
        $html .= Html::beginTag('li');
        $html .= Html::a(
            '<i class="ri-file-pdf-2-line"></i> As PDF',
            Url::to(['pdf-export', 'model' => $this->safeGetClass($this->export_model)]),
            ['class' => 'dropdown-item']
        );
        $html .= Html::endTag('li');

        $html .= Html::endTag('ul');
        $html .= Html::endTag('div');

        return $html;
    }
}

/**
 *
 * Usage example
 * <?= PageHeaderWidget::widget([
 *     'page' => 'Users Management',
 *     'path' => 'user/create',
 *     'button' => 'Add User',
 *     'export_model' => User::class,
 *     'searchFields' => $searchFields,
 *     'model' => $searchModel,
 *     'additional_buttons' => [
 *         [
 *             'label' => 'Import Users',
 *             'url' => ['user/import'],
 *             'icon' => 'ri-upload-line',
 *             'options' => [
 *                 'class' => 'btn btn-success',
 *                 'data-toggle' => 'modal',
 *                 'data-target' => '#importModal',
 *                 'id' => 'import-btn'
 *             ]
 *         ]
 *     ]
 * ]) ?>
 */
