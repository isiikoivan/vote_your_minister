<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="candidate-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
            <?= $form->field($model, 'student_id')->dropDownList(
                    \yii\helpers\ArrayHelper::map(\app\models\User::find()->where(['role'=>'STUDENT'])->all(),'id','firstname'),
                    ['prompt' => 'Select Student']
            ) ?>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-3">
            <?= $form->field($model, 'position_id')->dropDownList(
                \yii\helpers\ArrayHelper::map(\app\models\Position::find()->all(),'id','name'),
                ['prompt' => 'Select Post']
            ) ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
