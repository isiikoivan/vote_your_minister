<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Position $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="position-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-lg-6 col-md-12 col-12 mb-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-12 col-12 mb-3">

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-12 col-12 mb-3">

            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>

        <!-- <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?> -->
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
