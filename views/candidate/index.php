<?php

use app\models\Candidate;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\CandidateSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Candidates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="candidate-index mt-5">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a(Yii::t('app', 'Create Candidate'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['label' => 'Candidate Name',
                'attribute' => 'full_name',
                'value' => function ($model) {
                    return $model['full_name']; // not $model->full_name
                }

            ],
            [
                'label' => 'Position',
                'attribute' => 'name',
                'value' => function ($model) {
                    return $model['name']; // not $model->full_name
                }

            ],
//            'position_id',

            // 'created_by',
            // 'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Candidate $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
