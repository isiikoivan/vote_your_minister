<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?= $form->field($model, 'firstname')->textInput() ?>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?= $form->field($model, 'lastname')->textInput() ?>
        </div>
        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?= $form->field($model, 'course')->textInput() ?>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?= $form->field($model, 'year')->textInput([
                'type' => 'number',
//                'min' => 1900,
                'max' => date('Y'),
                'placeholder' => 'Enter year'
            ]) ?>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?= $form->field($model, 'regNo')->textInput() ?>
        </div>


        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?php $data = ["ADMIN" => "Admin", "STUDENT" => "Student"] ?>
            <?= $form->field($model, 'role')->dropDownList(
                $data,
                ['prompt' => 'Select Role']) ?>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-3">
            <?php $data = ["ACTIVE" => "Active", "IN_ACTIVE" => "In Active"] ?>
            <?= $form->field($model, 'status')->dropDownList(
                $data,
                ['prompt' => 'Set Status']) ?>
        </div>

        <div class="col-lg-6 col-md-12 col-12 mb-3">
            <?= $form->field($model, 'image')->fileInput() ?>
        </div>

        <div class="col-lg-6 col-md-6 col-12 mb-3" style="display: <?=$model->isNewRecord ? '' : 'none'?>">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
    </div>

    <div class="form-group">
<!--        --><?php //= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
