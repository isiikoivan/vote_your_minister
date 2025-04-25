<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */

$this->title = Yii::t('app', 'Update Candidate: {name}', [
    'name' => $model->name,
]);
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Candidates'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
 $this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="candidate-update">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
