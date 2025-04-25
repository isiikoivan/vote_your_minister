<?php

/** @var yii\web\View $this */
use yii\helpers\Url;
$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Makerere Business institution voting App!</h1>

        <p class="lead">You’re just a few clicks away from making a difference.
    </div>

    <div class="body-content">
        <div class="jumbotron text-center bg-transparent mt-5 mb-5">
            <img src="<?=Url::to("@web/web/assets/vote_clear.png")?>" alt="logo">
        </div>
        <div class="row justify-content-center align-items-center bg-success  text-center mb-5">
                <p><a class="btn btn-outline-light align-self-center align-items-center mt-2" href=<?=Url::to(["/site/login"])?> >Login &raquo;</a></p>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-3 text-center">
                <h2>Register</h2>

                <p>
                Register to vote in the upcoming elections for university leaders, including the Guild President! Your vote shapes the future of our campus by choosing leaders who will represent your interests and make a difference. Don’t miss the chance to have your voice heard—register today!
</p>
<!--                <p><a class="btn btn-outline-secondary" href=< ?=Url::to('/student/create')?>>Register &raquo;</a></p>-->
            </div>
            <div class="col-lg-4 mb-3 text-center">
                <h2>Candidates</h2>

                <p>
                Candidates for Guild President focus on student welfare, inclusivity, and digital learning. Vice President candidates aim to enhance career services, student clubs, and campus events. Finance Secretary candidates prioritize financial transparency or securing funding for student projects.
                </p>

<!--                <p><a class="btn btn-outline-secondary" href="https://www.yiiframework.com/forum/">View Candidates &raquo;</a></p>-->
            </div>
            <div class="col-lg-4 text-center">
                <h2>Result (live feed)</h2>

                <p>
                Voting for the Student Guild Elections is currently underway. Students are encouraged to cast their votes for Guild President, Vice President, and Finance Secretary. Each candidate has shared their vision for a better campus, and your vote plays a crucial role in shaping the future of student leadership. Remember, voting ends on <strong><?=date("F j, Y") ?></strong>, so don’t miss your chance to have your say!                </p>

<!--                <p>-->
<!--                    <a class="btn btn-outline-secondary" href="https://www.yiiframework.com/extensions/">Current Results &raquo;</a>-->
<!--                </p>-->
            </div>
        </div>

    </div>
</div>
