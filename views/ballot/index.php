<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\BallotSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Ballots');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ballot-index">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <p class="text-end "style="display: <?=(yii::$app->user->identity->role=='ADMIN')?'none':''?>">
        <?= Html::a(Yii::t('app', 'Vote Ballot'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'position',

            ['label' => 'Candidates',
                'attribute' => 'full_name',
            ],
            'votes',
//            'candidate_id',
//            'vote',
//            'created_at',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Ballot $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
