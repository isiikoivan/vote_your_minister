<?php

use app\models\Position;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\SearchExportWidget;



/** @var yii\web\View $this */
/** @var app\models\PositionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Candidate Position');
 $this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-index">

    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>

    <p class="text-end">
        <?= Html::a(Yii::t('app', 'Create Position'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            'description',
            'code',
            // 'created_by',
//            'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Position $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>


    <?= SearchExportWidget::widget([
    'model' => $searchModel,
    'searchFields' => [
    ['name' => 'name', 'type' => 'text', 'placeholder' => 'Enter name'],
    ['name' => 'created_at', 'type' => 'date', 'placeholder' => 'Select date'],
//    ['name' => 'status', 'type' => 'select', 'placeholder' => 'Select status', 'options' => ['Active' => 'Active', 'Inactive' => 'Inactive']],
    ],
    'exportModel' => $searchModel,
    'path' => 'controller/create',
    'buttonText' => 'Add New',
    'title' => 'Search and Export',
    ])?>

    <?= \app\widgets\FlashMessage::widget([
        'useDismiss' => true,
        'autoFade' => true,
        'fadeTimeout' => 4000
    ]); ?>

<!--    --><?php //= TableGeneratorWidget::widget([
//    'model' => $searchModel,
//    'data' => null,
//    'provider' => $dataProvider,
//    ]);?>

    <?=\app\widgets\TableGenerator::table(model:$searchModel, data_in: $dataProvider->getModels())?>


</div>
