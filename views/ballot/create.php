<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Ballot $model */
/** @var app\models\Ballot $dataProvider */
/** @var app\models\Ballot $dataProviderCandidate */

$this->title = Yii::t('app', 'Ballot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ballots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ballot-create">
    <?php
    //    var_dump($dataProvider);
    ?>
    <h1 class="mt-5"><?= Html::encode($this->title) ?></h1>
    <h4 class="mt-4"><?= "Your Vote Your Power (Endevor not to have an invalid Vote)" ?></h4>
    <hr>
<div class="row">
    <table class="table table-striped table-hover table-bordered">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Position Name</th>
            <th scope="col">Number of Participant</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>

        <?php
        $n=0;
        foreach ($dataProviderCandidate as $candidate) {
//            var_dump($candidate);
            $n++;
        ?>
        <tr>
            <th scope="row"><?=$n?></th>
            <td>
                <?= $candidate->name?>
            </td>
            <td>
                <?= $candidate->candidate_no?>

            </td>
            <td class="text-center">
              <a class="btn btn-outline-secondary" href=<?= Url::to(['ballot/view-group-candidate','id'=>$candidate->position_id,'position_name'=>$candidate->name]) ?>>View Candidate(s)
                    </a>
            </td>
        </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>



</div>
