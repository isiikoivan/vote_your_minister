<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
//    public function actionCreate()
//    {
//        $model = new User();
//        $user_data = Yii::$app->request->post();
//
//        $model->password = !empty($user_data['User']['password']) ? Yii::$app->getSecurity()->generatePasswordHash($user_data['User']['password']) : '12345';
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save(false)) {
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }

//    public function actionCreate()
//    {
//        $model = new User();
//
//        if ($this->request->isPost) {
//            $user_data = Yii::$app->request->post('User');
//
//            // Generate a hashed password from user input or use a default
//            $model->password = !empty($user_data['password'])
//                ? Yii::$app->getSecurity()->generatePasswordHash($user_data['password'])
//                : Yii::$app->getSecurity()->generatePasswordHash('12345');
//
//            // Load the posted data into the model
//            $model->load($user_data);
//
//            // Handling image upload
//            $imageFile = UploadedFile::getInstance($model, 'image'); // Assuming 'imageFile' is the input name
//
//            if ($imageFile) {
//                // Convert the uploaded image to a Base64 encoded string
//                $model->image_name = $imageFile->baseName . '.' . $imageFile->extension;
//                $imageData = file_get_contents($imageFile->tempName); // Get image data from the temp file
//                $base64 = base64_encode($imageData); // Encode the image as Base64
//                $model->image = 'data:' . $imageFile->type . ';base64,' . $base64; // Store the Base64 string with data URL format
//            }
//
//            // Save the model to the database
//            if ($model->save(false)) { // Save without validation for password unless you want validation for other attributes
//                return $this->redirect(['view', 'id' => $model->id]);
//            } else {
//                Yii::trace($model->errors); // Log any errors for debugging
//            }
//        } else {
//            $model->loadDefaultValues(); // Load default values if not a POST request
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            $user_data = Yii::$app->request->post('User');
//var_dump($user_data['']);return;
            // Generate a hashed password from user input or use a default
            $model->password = !empty($user_data['password'])
                ? Yii::$app->getSecurity()->generatePasswordHash($user_data['password'])
                : Yii::$app->getSecurity()->generatePasswordHash('12345');
            $model->firstname = $user_data['firstname'];
            $model->lastname = $user_data['lastname'];
            $model->email = $user_data['email'];
            $model->course = $user_data['course'];
            $model->year = $user_data['year'];
            $model->role = $user_data['role'];
            $model->status = $user_data['status'];
            $model->regNo = $user_data['regNo'];
            // Load the posted data into the model
            $model->load($user_data);

            $imageFile = UploadedFile::getInstance($model, 'image'); // Assuming 'imageFile' is the input name

            if ($imageFile) {
                $model->image = $imageFile->baseName . '.' . $imageFile->extension; // Store original file name or create another storage logic
            }

            // Save the model to the database
            if ($model->save()) { // Save without validation for password unless you want validation for other attributes
                if ($imageFile) {
                    // Define the path where the image will be stored
                    $path = "web/assets/uploads/" . $model->image; // Adjust the path as needed
//                    $path = '@web/assets/user_images/' . $model->image; // Adjust the path as needed

                    // Save the uploaded file
                    $imageFile->saveAs($path);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::trace($model->errors); // Log any errors for debugging
            }
        } else {
            $model->loadDefaultValues(); // Load default values if not a POST request
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
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
     * Deletes an existing User model.
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
}
