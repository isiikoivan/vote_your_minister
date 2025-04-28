<?php
namespace components;

//use app\widgets\SearchableDropDown;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii;
use yii\helpers\Url;

trait GenHelperTrait
{

private $width_scale = 0;

    public function actionExcelExport()
    {
        $modelName = Yii::$app->request->get('model');
        if (!empty($modelName)) {
            $modelClass = $modelName;
            if (class_exists($modelClass)) {

                $model = new $modelClass();

                ExcelPdfGenerator::exportExcel($model);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPdfExport()
    {
        $modelName = Yii::$app->request->get('model');
        if (!empty($modelName)) {
            $modelClass = $modelName;
            if (class_exists($modelClass)) {
                $model = new $modelClass();
                ExcelPdfGenerator::generatePdf($model, CrossHelperCopy::modelNameResolver($model));
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }



//    public function actionPdfExport($model, $params = null)
//    {
//        // Initialize the search model and apply filters
//        $searchModel = new $model();
//        $searchModel->load($params); // Apply the filtering parameters
//        $dataProvider = $searchModel->search($params); // Get the filtered data provider
//
//        // Trigger PDF generation with filtered data
//        ExcelPdfGenerator::generatePdf($searchModel, [
//            'title' => 'Exported Data',
//            'filename' => 'filtered_data_export', // Custom filename
//            'params' => $params, // Pass query params to ensure the PDF is filtered
//        ]);
//    }


    public function searchComponent($model, $searchFields)
    {
        $form = ActiveForm::begin(['method' => 'get']);

        // Start the HTML output
        echo '<div class=" search-field d-flex col-12 align-items-center gap-3">';

        if (!empty($searchFields)) {
            foreach ($searchFields as $field) {

                $inputOptions = ['class' => 'form-control', 'placeholder' => $field['placeholder']];

                // Generate input based on field type
                switch ($field['type']) {
                    case 'date':
                        echo '<div class=" col-lg-3  col-md-6 col-sm-12">' . $form->field($model, $field['name'])->input('date', $inputOptions)->label(false) . '</div>';
                        break;
                    case 'select':
                        $slt_placeholder = $field['placeholder'];
                        echo '<div class=" col-lg-3  col-md-6 col-sm-12">' . $form->field($model, $field['name'])->dropDownList([$slt_placeholder] + $field['options'] ?? [], $inputOptions)->label(false) . '</div>';
                        break;
                    case 'select2':
                        $slt_placeholder = $field['placeholder'];
                        echo '<div class=" col-lg-3  col-md-6 col-sm-12">' . $form->field($model, $field['name'])->widget(SearchableDropDown::class, [
                                'data' => [$slt_placeholder] + $field['options'] ?? [],
                                'placeholder' => $field['placeholder'],
                                'options' => ['style' => 'width: 100%;']
                            ])->label(false) . '</div>';
                        break;


                    case 'text':
                    default:
                        echo '<div class=" col-lg-3  col-md-6 col-sm-12">' . $form->field($model, $field['name'])->textInput($inputOptions)->label(false) . '</div>';
                        break;
                }

            }
        }

        // Submit button
        echo '<div class=" col-lg-4  col-md-6 col-sm-12">';
        echo Html::submitButton('Search', ['class' => 'btn btn-primary']);
        echo '</div>';

        ActiveForm::end();
    }

    public function renderSearchAndExportSection($model = null, $searchFields = null, $export_model = null, $path = "", $button = "", $title = "", $exportVisibility = true, $buttonVisibility = true, $searchFieldsVisibility = true)
    {
        // Start the HTML output for the section
        ob_start(); // Start output buffering
        ?>

        <div class="row mb-3 ">
            <div class="col-12">
                <h2><?= Html::encode($title) ?></h2>
            </div>
        </div>
        <div class="row mb-2 justify-content-between">
            <div class="col-lg-10 col-md-8 col-sm-12 ">
                <?php if ($searchFieldsVisibility): ?>
                    <?php $form = ActiveForm::begin(['method' => 'get']); ?>
                    <div class="row">

                        <?php if (!empty($searchFields)):
                            $field_counter = count($searchFields);
                            $fc = ($field_counter <= 4) ? $field_counter + 1 : $field_counter;
                            $this->width_scale = ($field_counter < 1) ? 1 : floor(12 / $fc);

                            ?>
                            <?php foreach ($searchFields as $field): ?>

                            <div class="col-lg-<?= $this->width_scale; ?> col-md-<?= $this->width_scale * 2; ?> col-sm-12">
                                <?php
                                $inputOptions = ['placeholder' => $field['placeholder']];
                                switch ($field['type']) {
                                    case 'date':
                                        echo $form->field($model, $field['name'])->input('date', $inputOptions)->label(false);
                                        break;
                                    case 'select':
                                        $slt_placeholder = $field['placeholder'];
                                        echo $form->field($model, $field['name'])->dropDownList( $field['options'] ?? [], $inputOptions)->label(false);
                                        break;
                                    case 'select2':
                                        $slt_placeholder = $field['placeholder'];
                                        echo $form->field($model, $field['name'])->widget(SearchableDropDown::class, [
                                            'data' =>  $field['options'] ?? [],
                                            'options' => ['placeholder' => $slt_placeholder,'style' => 'width: 100%;']
                                        ])->label(false);
                                        break;
                                    default:
                                        echo $form->field($model, $field['name'])->textInput($inputOptions)->label(false);
                                        break;
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="col-lg-<?=$this->width_scale?> col-md-<?=$this->width_scale * 2 ?> col-sm-12"
                             style="width: fit-content">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12 mt-2 align-items-end"
                 style="width: fit-content; margin-right: 2em;">
                <div class="row  align-items-center">
                    <?php if ($exportVisibility): ?>
                        <div class="dropdown " style="width: fit-content;">
                            <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="ri-article-fill"></i>
                                <span>Export</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?= Url::to(['excel-export', 'model' => $this->safeGetClass($export_model)]) ?>"
                                       class="dropdown-item ml-2"><i class="ri-file-excel-2-line"></i> As Excel</a></li>
                                <li>
                                    <a href="<?= Url::to(['pdf-export', 'model' => $this->safeGetClass($export_model)]) ?>"
                                       class="dropdown-item"><i class="ri-file-pdf-2-line"></i> As PDF</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($buttonVisibility): ?>
                        <div style="width: auto; padding: 0;">
                            <a href="<?= Url::to([$path]) ?>" class="btn btn-primary text-truncate"
                               title="<?= $button ?>"
                               aria-label="<?= Html::encode($button) ?>">Create</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <div class="row align-self-center">
            <hr class="border-bottom border-bottom-3 border-dark-subtle  gap-2">
        </div>


        <?php
        // Capture the output and return it as a string
        return ob_get_clean();
    }

    public function safeGetClass($object)
    {
        if (is_string($object)) {
            return $object;
        }
        return is_object($object) ? get_class($object) : '';
    }


}
