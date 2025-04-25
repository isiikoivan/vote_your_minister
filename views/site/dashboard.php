<?php

/** @var yii\web\View $this */

use app\models\Candidate;
use app\models\User;
use yii\helpers\Html;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
$displayer = (yii::$app->user->identity->role =='STUDENT' )?'none':'';
?>
<div class="site-about mt-5">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        This is the dashboard page. Displays Summary of Users under a given category.:
    </p>

    <!--    <code>--><?php //= __FILE__ ?><!--</code>-->
    <div class="row">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4" style="display: <?=$displayer?>"> <!-- Added mb-4 for margin-bottom -->
                <div class="card text-white bg-primary">
                    <div class="card-body ">
                        <h5 class="card-title">System Users</h5>
                        <p class="card-text fs-1 text-center">
                            <?= User::find()->count('id'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4"> <!-- Added mb-4 for margin-bottom -->
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Students</h5>
                        <p class="card-text text-center fs-1">
                            <?= User::find()->where(['role' => 'STUDENT'])->count('id'); ?>

                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4" style="display: <?=$displayer?>"> <!-- Added mb-4 for margin-bottom -->
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">ADMINS</h5>
                        <p class="card-text text-center fs-1">
                            <?= User::find()->where(['role' => 'ADMIN'])->count('id'); ?>

                        </p></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4"> <!-- Added mb-4 for margin-bottom -->
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">Candidates</h5>
                        <p class="card-text text-center fs-1">
                            <?= Candidate::find()->count('id'); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
