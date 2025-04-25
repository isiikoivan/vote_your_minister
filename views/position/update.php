<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Position $model */

$this->title = Yii::t('app', 'Update Position: {name}', [
    'name' => $model->name,
]);
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Positions'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
 $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="position-update mt-5">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
