<?php

namespace app\controllers;

use app\models\Candidate;
use app\models\CandidateSearch;
use components\GenHelperTrait;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CandidateController implements the CRUD actions for Candidate model.
 */
class CandidateController extends Controller
{
    use GenHelperTrait;
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
     * Lists all Candidate models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CandidateSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Candidate model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionView($id)
//    {
//       $query = Candidate::find()
//            ->select([
//                'c.*',
//                "concat(u.firstname,' ' ,u.lastname) as full_name",
//                'p.code',
//                'p.description',
//                'p.name'
//            ])
//            ->from('candidate as c')
//            ->innerJoin('user AS u', 'u.id = c.student_id')
//            ->innerJoin('position AS p', 'p.id = c.position_id')
//       ->where(['c.id' => $id]);
//
//        return $this->render('view', [
//            'model' => $query,
//        ]);
//    }
    public function actionView($id)
    {
        $model = Candidate::find()
            ->select([
                'c.*',
                "CONCAT(u.firstname, ' ', u.lastname) AS full_name",
                'p.code',
                'p.description',
                'p.name AS position_name'
            ])
            ->from('candidate AS c')
            ->innerJoin('user AS u', 'u.id = c.student_id')
            ->innerJoin('position AS p', 'p.id = c.position_id')
            ->where(['c.id' => $id])
//            ->asArray()
            ->one(); // Get a single result as array

        if ($model === null) {
            throw new \yii\web\NotFoundHttpException("Candidate not found.");
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Candidate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Candidate();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Candidate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
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
     * Deletes an existing Candidate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Candidate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Candidate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Candidate::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
