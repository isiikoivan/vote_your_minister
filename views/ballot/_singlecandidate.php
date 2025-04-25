<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Ballot $model */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\widgets\ActiveForm $dataProviderSingleCandidate */
$this->title = Yii::t('app', 'Your choice Matters');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ballots'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

$dataProviderSingleCandidate
?>
<h1 class="mt-5"><?= Html::encode($this->title) ?></h1>
<h4 class="mt-4 text-center"><?= "Your Vote Your Power (Choose your Leader) " ?></h4>

<div class="row mt-4">
    <?php
    //    var_dump($dataProviderSingleCandidate->query);
    $single_candidate = $dataProviderSingleCandidate->query;

    ?>
    <hr>
    <h4 class="mt-1 mb-1 text-center"> <?= $single_candidate['pos_name'] ?></h4>
    <hr>
    <div class="col-lg-3 col-md-6 col-sm-12 mb-4"> <!-- Added mb-4 for margin-bottom -->
        <div class="card text-white border-1">
            <div class="card-header text-start">

                <div class="card-body text-center">
                    <img width="200px" height="250px"
                         src="<?= Url::to("@web/web/assets/uploads/" . $single_candidate['image']) ?>" alt="logo">
                </div>

            </div>
        </div>

    </div>
    <div class="col-lg-9 col-md-6 col-sm-12 mb-4"> <!-- Added mb-4 for margin-bottom -->

        <div class="card text-white border-1">
            <div class="card-header text-start">
                <div class="card-body ">

                    <h5 class=" text-dark text-center">Candidate Details</h5>
                    <hr class="text-dark">
                    <table class="text-dark w-100 ">
                        <tbody>
                        <tr>
                            <td class="fw-bold">
                                <?= Html::encode("First Name") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['firstname']) ?>
                            </td>
                            <td class="fw-bold">
                                <?= Html::encode("Last Name") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['lastname']) ?>
                            </td>
                            <td class="fw-bold">
                                <?= Html::encode("Other Name") ?>
                            </td>
                            <td>
                                <?= "N/A" ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <?= Html::encode("Email") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['email']) ?>
                            </td>
                            <td class="fw-bold">
                                <?= Html::encode("Registration No.") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['regNo']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <?= Html::encode("Status") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['status']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">
                                <?= Html::encode("Course") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['course']) ?>
                            </td>
                            <td class="fw-bold">
                                <?= Html::encode("Year") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['year']) ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-bold">
                                <?= Html::encode("Post") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['pos_name']) ?>
                            </td>


                        </tr>

                        <tr>
                            <td class="fw-bold">
                                <?= Html::encode("Post Description") ?>
                            </td>
                            <td>
                                <?= Html::encode($single_candidate['pos_description']) ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr class="text-dark">

                </div>

            </div>
        </div>

    </div>
</div>




    <?php $form = ActiveForm::begin(['action' => ['ballot/cast-vote'],]); ?>

    <?= $form->field($model, 'candidate_id')->hiddenInput(['value' => $single_candidate['candidate_id']])->label(false) ?>

    <?= $form->field($model, 'position_id')->hiddenInput(['value' => $single_candidate['position_id']])->label(false) ?>

    <?= $form->field($model, 'vote')->hiddenInput(['value' => 1])->label(false) ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Cast Your Vote'),
            ['class' => 'btn btn-success',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to cast your vote?'),
                    'method' => 'post',
                ]
            ]) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
