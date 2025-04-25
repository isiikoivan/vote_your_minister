<?php

namespace app\controllers;

use app\models\Ballot;
use app\models\BallotSearch;
use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BallotController implements the CRUD actions for Ballot model.
 */
class BallotController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Ballot models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BallotSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ballot model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Ballot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ballot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ballot::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Ballot model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Ballot();
        $searchModel = new BallotSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProviderCandidate = $searchModel->searchCandidate($this->request->queryParams);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

//        return $this->render('create', [
//            'model' => $model,
//        ]);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderCandidate' => $dataProviderCandidate->getModels(),

            'model' => $model,
        ]);
    }

    public function actionViewGroupCandidate($id)
    {
        $model = new Ballot();
        $searchModel = new BallotSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProviderCandidate = $searchModel->searchViewCandidates($this->request->queryParams, null, $id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

//        return $this->render('create', [
//            'model' => $model,
//        ]);
        return $this->render('_allcandidate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderCandidate' => $dataProviderCandidate->getModels(),

            'model' => $model,
        ]);
    }

    public function actionViewSingleCandidate($id)
    {
        $model = new Ballot();
        $searchModel = new BallotSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProviderSingleCandidate = $searchModel->searchViewSingleCandidates($id);

        return $this->render('_singlecandidate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderSingleCandidate' => $dataProviderSingleCandidate,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ballot model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ballot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCastVote()
    {
        $ball = yii::$app->request->post();
//        var_dump($ball['Ballot']['candidate_id']);
//        var_dump(Yii::$app->user->identity->id);
//        return;
        $model = new Ballot();
        $model->candidate_id =$ball['Ballot']['candidate_id'];
        $model->position_id = $ball['Ballot']['position_id'];
        $model->vote = $ball['Ballot']['vote'];
        $model->voter_id = Yii::$app->user->identity->id;

        $model->save(false);

        return $this->redirect(yii::$app->request->referrer);
    }

}
