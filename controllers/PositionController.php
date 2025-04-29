<?php
namespace app\controllers;

use app\components\ExcelPdfGenerator;
use app\components\ExportHelper;
use app\models\Position;
use app\models\PositionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PositionController implements the CRUD actions for Position model.
 */
class PositionController extends Controller
{
//    use GenHelperTrait;
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
     * Lists all Position models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Position();
        $searchModel = new PositionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
//        $header = $this->renderSearchAndExportSection($searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
'model' => $model,
//            'header' => $header,
            'searchFields' => $searchModel->searchFields(),
            'export_model'=>$searchModel,
        ]);
    }

    /**
     * Displays a single Position model.
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
     * Creates a new Position model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Position();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Position model.
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
     * Deletes an existing Position model.
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
     * Finds the Position model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Position the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Position::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionPdfExport()
    {
        $modelName = \Yii::$app->request->get('model');
        if (!empty($modelName)) {
            $modelClass = $modelName;
            if (class_exists($modelClass)) {
                $model = new $modelClass();
                ExcelPdfGenerator::generatePdf($model, \app\widgets\CrossHelperCopy::modelNameResolver($model));
            }
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionExcelExport()
    {
        $modelName = \Yii::$app->request->get('model');
        if (!empty($modelName)) {
            $modelClass = $modelName;
            if (class_exists($modelClass)) {

                $model = new $modelClass();

                ExportHelper::exportExcel($model);
            }
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }
}
