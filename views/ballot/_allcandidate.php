<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Ballot $model */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\widgets\ActiveForm $dataProviderCandidate */
$this->title = Yii::t('app', 'Candidates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ballots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="mt-5"><?= Html::encode($this->title) ?></h1>
<h4 class="mt-4 text-center"><?= "Your Vote Your Power (Choose your Leader)   " .$_GET['position_name'] ?></h4>
<hr>
<h4 class="mt-4 text-center"> <?= $_GET['position_name'] ?></h4>
<hr>
<div class="row mt-4">

    <?php
    //        'dataProvider' => $dataProvider,
    //        'model' => $model,

    $all_leaders = [];
//                var_dump($dataProviderCandidate);
    foreach ($dataProviderCandidate as $candidate) {
//        var_dump($candidate);
        ?>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4"> <!-- Added mb-4 for margin-bottom -->
            <div class="card text-white border-1">

                <div class="card-body text-center text-dark">
                    <img width="200px" height="250px"
                         src="<?= \yii\helpers\Url::to("@web/web/assets/uploads/".$candidate->image) ?>" alt="No Image">
                </div>
                <div class="footer text-center">
                    <h5 class="card-title text-dark"><?= $candidate['full_name'] ?></h5>
                    <p><a class="btn btn-outline-secondary" href=<?= \yii\helpers\Url::to(['ballot/view-single-candidate','id'=>$candidate->student_id]); ?>>Continue
                        </a></p>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

