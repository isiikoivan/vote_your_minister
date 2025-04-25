<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */

$this->title = Yii::t('app', 'Set Student As Candidate');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Candidates'), 'url' => ['index']];
 $this->params['breadcrumbs'][] = $this->title;
?>
<div class="candidate-create">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
