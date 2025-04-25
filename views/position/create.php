<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Position $model */

$this->title = Yii::t('app', 'Create Position');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Positions'), 'url' => ['index']];
 $this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-create">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
