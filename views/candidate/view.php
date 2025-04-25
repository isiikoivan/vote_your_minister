<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Candidate $model */
$this->title = $model['full_name'];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Candidates'), 'url' => ['index']];
 $this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="candidate-view">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
//            'student_id',
//            'position_id',
            ['label' => 'Candidate Name',
                'attribute' => 'full_name',
                'value' => function ($model) {
                    return $model['full_name']; // not $model->full_name
                }

            ],
            [
                'label' => 'Position',
                'attribute' => 'position_name',
                'value' => function ($model) {
                    return $model['position_name']; // not $model->full_name
                }

            ],
            // 'created_by',
            // 'created_at',

        ],
    ]) ?>

</div>
