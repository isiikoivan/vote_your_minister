<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class SearchExportWidget extends Widget
{
    public $model;
    public $searchFields = [];
    public $exportModel;
    public $path = '';
    public $buttonText = 'Create';
    public $title = '';
    public $exportVisibility = true;
    public $buttonVisibility = true;
    public $searchFieldsVisibility = true;

    private $widthScale = 0;

    public function run()
    {
        return $this->renderSection();
    }

    protected function renderSection()
    {
        ob_start();

        if (!$this->model) {
            return '';
        }

        ?>
        <div class="row mb-3">
            <div class="col-12">
                <h2><?= Html::encode($this->title) ?></h2>
            </div>
        </div>

        <div class="row mb-2 justify-content-between">
            <div class="col-lg-10 col-md-8 col-sm-12">
                <?php if ($this->searchFieldsVisibility): ?>
                    <?php $form = ActiveForm::begin(['method' => 'get']); ?>
                    <div class="row">
                        <?php
                        $fieldCount = count($this->searchFields);
                        $this->widthScale = ($fieldCount < 1) ? 1 : floor(12 / ($fieldCount <= 4 ? $fieldCount + 1 : $fieldCount));

                        foreach ($this->searchFields as $field):
                            $inputOptions = ['placeholder' => $field['placeholder']];
                            ?>
                            <div class="col-lg-<?= $this->widthScale ?> col-md-<?= $this->widthScale * 2 ?> col-sm-12">
                                <?php
                                switch ($field['type']) {
                                    case 'date':
                                        echo $form->field($this->model, $field['name'])->input('date', $inputOptions)->label(false);
                                        break;
                                    case 'select':
                                        echo $form->field($this->model, $field['name'])->dropDownList($field['options'] ?? [], $inputOptions)->label(false);
                                        break;
                                    case 'select2':
                                        echo $form->field($this->model, $field['name'])->widget(SearchableDropDown::class, [
                                            'data' => $field['options'] ?? [],
                                            'options' => ['placeholder' => $field['placeholder'], 'style' => 'width: 100%;']
                                        ])->label(false);
                                        break;
                                    default:
                                        echo $form->field($this->model, $field['name'])->textInput($inputOptions)->label(false);
                                        break;
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="col-lg-<?= $this->widthScale ?> col-md-<?= $this->widthScale * 2 ?> col-sm-12" style="width: fit-content">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-12 mt-2 align-items-end" style="width: fit-content; margin-right: 2em;">
                <div class="row align-items-center">
                    <?php if ($this->exportVisibility): ?>
                        <div class="dropdown" style="width: fit-content;">
                            <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-article-fill"></i> <span>Export</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="<?= Url::to(['excel-export', 'model' => $this->safeGetClass($this->exportModel)]) ?>" class="dropdown-item"><i class="ri-file-excel-2-line"></i> As Excel</a></li>
                                <li><a href="<?= Url::to(['pdf-export', 'model' => $this->safeGetClass($this->exportModel)]) ?>" class="dropdown-item"><i class="ri-file-pdf-2-line"></i> As PDF</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->buttonVisibility): ?>
                        <div style="width: auto; padding: 0;">
                            <a href="<?= Url::to([$this->path]) ?>" class="btn btn-primary text-truncate" title="<?= $this->buttonText ?>">
                                <?= Html::encode($this->buttonText) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-12">
              <hr class="border-bottom border-bottom-3 border-dark-subtle">
          </div>
        </div>

        <?php
        return ob_get_clean();
    }

    protected function safeGetClass($object)
    {
        if (is_string($object)) {
            return $object;
        }
        return is_object($object) ? get_class($object) : '';
    }


}
