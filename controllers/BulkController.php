<?php

namespace app\controllers;

use app\components\Helpers;
use app\models\Budget;
use app\models\Expenses;
use app\models\MainBudget;
use app\models\Staff;
use app\models\User;
use Exception;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\UploadedFile;

class BulkController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    // sample break through for uploading excel files
    public function actionUploadExcel()
    {
        $file_temp = 'web/assets/main_budget_fire.xls';
        try {

            // $inputFileType

            $inputFileType = IOFactory::identify($file_temp);
            $objReader = IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($file_temp);
        } catch (Exception $e) {
            // die('Error');
            return yii::$app->session->setFlash('danger', 'File not uploaded');
        }


        $sheet = $objPHPExcel->getSheet(0);
        $heighestRow = $sheet->getHighestRow();
        $heightsCol = $sheet->getHighestColumn();
        for ($row = 1; $row <= $heighestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $heightsCol . $row, NULL, TRUE, FALSE);
            if ($row == 1) {
                continue;
            }

            $main_budget_info = new MainBudget();
            $main_budget_info->main_budget_name = $rowData[0][0];
            $main_budget_info->main_budget_category = $rowData[0][1];
            $main_budget_info->main_budget_warning_limit = $rowData[0][2];
            $main_budget_info->main_budget_amount_limit = $rowData[0][3];
            $main_budget_info->start_date = date('Y-m-d', strtotime($rowData[0][4]));
            $main_budget_info->end_date = date('Y-m-d', strtotime($rowData[0][5]));
            $main_budget_info->created_by = Helpers::LoggedInUserData()->id;
            $main_budget_info->organisation_id = Helpers::LoggedInUserData()->organisation_id;
            // print_r($main_budget_info->getErrors());
            $main_budget_info->save(false);
        }
        // die('okay');
        return yii::$app->session->setFlash('success', 'Information Uploaded Successfully');
    }

    // uploading organisatinal budget
    public function actionOrganisationBudgetUpload()
    {
        $primary_budget_model = new MainBudget(); //creating object

        if ($primary_budget_model->load(yii::$app->request->post())) {

            // var_dump(($_FILES));
            // return;
            // pick file on upload
            $file_content = UploadedFile::getInstance($primary_budget_model, 'main_budget_upload');
            // align uploadded instance to  the modal
            $primary_budget_model->main_budget_upload = $file_content;
            // var_dump($file_content);
            // return;
            // check upload availability 
            if ($primary_budget_model->main_budget_upload) {

                // uploading file to uploads directory
                $primary_budget_model->main_budget_upload->saveAs('uploads/' . $primary_budget_model->main_budget_upload->baseName . '.' . $primary_budget_model->main_budget_upload->extension);

                // attain  file uploaded path
                // $file = 'web/images/main_budget.xls';
                // /var/www/html/requixa/uploads
                $file_path_link = 'uploads/' . $primary_budget_model->main_budget_upload->baseName . '.' . $primary_budget_model->main_budget_upload->extension;

                // break down to upload budget dynamically
                // $file = (string)$file_path_link;
                $file = (string)$file_path_link;
                // $file = 'uploads/main_budget_try.xls';

                // var_dump($file);
                // return;

                try {

                    // $inputFileType

                    $inputFileType = IOFactory::identify($file);
                    $objReader = IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($file);
                } catch (Exception $e) {
                    // die('Error');
                    return yii::$app->session->setFlash('danger', 'File not uploaded');
                }


                $sheet = $objPHPExcel->getSheet(0);
                $heighestRow = $sheet->getHighestRow();
                $heightsCol = $sheet->getHighestColumn();

                for ($row = 1; $row <= $heighestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $heightsCol . $row, NULL, TRUE, FALSE);
                    if ($row == 1) {
                        continue;
                    }

                    $main_budget_info = new MainBudget();
                    $main_budget_info->main_budget_name = $rowData[0][0];
                    $main_budget_info->main_budget_category = $rowData[0][1];
                    $main_budget_info->main_budget_warning_limit = $rowData[0][2];
                    $main_budget_info->main_budget_amount_limit = $rowData[0][3];
                    $main_budget_info->start_date = date('Y-m-d', strtotime($rowData[0][4]));
                    $main_budget_info->end_date = date('Y-m-d', strtotime($rowData[0][5]));
                    $main_budget_info->created_by = Helpers::LoggedInUserData()->id;
                    $main_budget_info->organisation_id = Helpers::LoggedInUserData()->organisation_id;
                    // print_r($main_budget_info->getErrors());
                    $main_budget_info->save(false);
                }


                // $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
                // $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                // $objPHPExcel = $objReader->load($file);
                // // var_dump($objReader);
                // // return;
                // $transaction = Yii::$app->db->beginTransaction();
                // try {

                //     $transaction->commit();
                // } catch (Exception $e) {

                //     $transaction->rollBack();
                // }
                unlink($file);


                // var_dump($file_path_link);
                // return;


                Yii::$app->session->setFlash('success', 'Organisation Budget Uploaded Successfully ');
                $this->redirect(['site/finance']);
            } else {
                // var_dump('exit on failure');
                // return;
                Yii::$app->session->setFlash('danger', 'Organisation Budget Not Uploaded ');
                $this->redirect(['site/finance']);
            }
            // var_dump('faaa');
            // return;


        }
    }

    // uploading  excel file data for staff
    public function actionStaffUpload()
    {
        ini_set("max_execution_time", 0);
        $staff = new Staff(); //creating object

        if ($staff->load(yii::$app->request->post())) {


            // extracting ModelType or Name (which is incoming)
            $model_keys = yii::$app->request->post();
            // var_dump($model_keys);
            // return;
            $modelRendered = array_keys(yii::$app->request->post());
//             var_dump($modelRendered[0]);
//             return;
            $modalTypeValue = $modelRendered[0];
            // var_dump($modalTypeValue);
            // return;
            // initialise department_id
            // department _id 
            $depart_id = $model_keys[$modalTypeValue]['department'];
            $user_role = $model_keys[$modalTypeValue]['user_permission'];

            // var_dump(($_FILES));
            // return;
            // pick file on upload
            $file_content = UploadedFile::getInstance($staff, 'staff_upload');
            // align uploadded instance to  the modal
            $staff->staff_upload = $file_content;
            // var_dump($file_content);
            // return;
            // check upload availability 
            if ($staff->staff_upload) {

                // uploading file to uploads directory
                $staff->staff_upload->saveAs('uploads/' . $staff->staff_upload->baseName . '.' . $staff->staff_upload->extension);
                // attain  file uploaded path

                $file_path_link = 'uploads/' . $staff->staff_upload->baseName . '.' . $staff->staff_upload->extension;

                // break down to upload  dynamically
                // $file = (string)$file_path_link;
                $file = (string)$file_path_link;
                // $file = 'uploads/main_budget_try.xls';

                // var_dump($file);
                // return;

                try {

                    // $inputFileType

                    $inputFileType = IOFactory::identify($file);
                    $objReader = IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($file);
                } catch (Exception $e) {
                    // die('Error');
                    return yii::$app->session->setFlash('danger', 'File not uploaded');
                }


                $sheet = $objPHPExcel->getSheet(0);
                $heighestRow = $sheet->getHighestRow();
                $heightsCol = $sheet->getHighestColumn();

                for ($row = 1; $row <= $heighestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $heightsCol . $row, NULL, TRUE, FALSE);
                    if ($row == 1) {
                        continue;
                    }

                    $staff_info = new Staff();
                    $staff_info->first_name = $rowData[0][0];
                    $staff_info->last_name = $rowData[0][1];
                    $staff_info->phone_contact = $rowData[0][2];
                    $staff_info->email_address = $rowData[0][3];
                    $staff_info->nature_of_employment = $rowData[0][4];
                    $staff_info->gender = $rowData[0][5];
                    $staff_info->user_level = $rowData[0][6];
                    $staff_info_dep_code =  $rowData[0][7];
                    $department_id_data = (new Query())->from('departments')->select(['*'])->where(['department_code' => $staff_info_dep_code])->one();
//                    var_dump($department_id_data['department_code']);
//                    return;
                    $staff_info->department = $department_id_data ? $department_id_data['id'] : null;

                    $staff_info->created_by = Helpers::LoggedInUserData()->id;
                    $staff_info->organisation_id = Helpers::LoggedInUserData()->organisation_id;
                    $staff_info->save(false);

                    // uploading into user too
                    // generating username and password

                    $username = Helpers::generateUsername(8);
                    $password = Helpers::generatePassword(8);


                    // intialising user model (adding user)

                    $user_model = new User();
                    // $staff_id = yii::$app->db->createCommand('select * from get_staff_id_into_user_id');
                    //  modification here   
                    $user_model->staff_id = $staff_info->id;
                    $user_model->first_name = $staff_info->first_name;
                    $user_model->last_name = $staff_info->last_name;
                    $user_model->mobile_number = "0" . (string)$staff_info->phone_contact;
                    $user_model->email_address = $staff_info->email_address;
                    $user_model->username = $username;
                    $user_model->user_level = $staff_info->user_level;
                    $user_model->user_permissions = $staff_info->user_level;
                    $user_model->password = Yii::$app->getSecurity()->generatePasswordHash($password);
                    $user_model->created_by = (int)Helpers::LoggedInUserData()->id;
                    $user_model->organisation_id = (int)Helpers::LoggedInUserData()->organisation_id;
                    $user_model->save(false);

                    // sending email on users email
                    Yii::$app->mailer->compose()
                        ->setFrom('officetool@servicecops.com')
                        ->setTo($user_model->email_address)
                        ->setSubject('Requixa logins')
                        ->setTextBody('you welcome to requixa a requisition managment system for you')
                        ->setHtmlBody(' <b>Username :</b> ' . $username . '<br><b>Password :</b> ' . $password . '<br><br>Click <a href="https://officetooluat.servicecops.com">Login</a>')
                        ->send();
                }


                // $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
                // $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                // $objPHPExcel = $objReader->load($file);
                // // var_dump($objReader);
                // // return;
                // $transaction = Yii::$app->db->beginTransaction();
                // try {

                //     $transaction->commit();
                // } catch (Exception $e) {

                //     $transaction->rollBack();
                // }
                unlink($file);


                // var_dump($file_path_link);
                // return;


                Yii::$app->session->setFlash('success', ' Staff Uploaded Successfully ');
                $this->redirect(['site/staff']);
            } else {

                // var_dump('exit on failure');
                // return;
                Yii::$app->session->setFlash('danger', 'Staff Not Uploaded ');
                $this->redirect(['site/staff']);
            }
            // var_dump('faaa');
            // return;


        }
    }

    // uploading bulky expense
    public function actionExpenseUpload()
    {
        $expense_detail = new Expenses(); //creating object

        if ($expense_detail->load(yii::$app->request->post())) {

            // var_dump(($_FILES));
            // return;
            // pick file on upload
            $file_content = UploadedFile::getInstance($expense_detail, 'expense_upload');
            // align uploadded instance to  the modal
            $expense_detail->expense_upload = $file_content;
            // var_dump($file_content);
            // return;
            // check upload availability 
            if ($expense_detail->expense_upload) {

                // uploading file to uploads directory
                $expense_detail->expense_upload->saveAs('uploads/' . $expense_detail->expense_upload->baseName . '.' . $expense_detail->expense_upload->extension);

                // attain  file uploaded path
                // $file = 'web/images/main_budget.xls';
                // /var/www/html/requixa/uploads
                $file_path_link = 'uploads/' . $expense_detail->expense_upload->baseName . '.' . $expense_detail->expense_upload->extension;

                // break down to upload budget dynamically
                // $file = (string)$file_path_link;
                $file = (string)$file_path_link;
                // $file = 'uploads/main_budget_try.xls';

                // var_dump($file);
                // return;

                try {

                    // $inputFileType

                    $inputFileType = IOFactory::identify($file);
                    $objReader = IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($file);
                } catch (Exception $e) {
                    // die('Error');
                    return yii::$app->session->setFlash('danger', 'File not uploaded');
                }


                $sheet = $objPHPExcel->getSheet(0);
                $heighestRow = $sheet->getHighestRow();
                $heightsCol = $sheet->getHighestColumn();

                for ($row = 1; $row <= $heighestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $heightsCol . $row, NULL, TRUE, FALSE);
                    if ($row == 1) {
                        continue;
                    }

                    $exp_detail = new Expenses();
                    $exp_detail->expense_category = $rowData[0][0];
                    $exp_detail->created_by = Helpers::LoggedInUserData()->id;
                    $exp_detail->organisation_id = Helpers::LoggedInUserData()->organisation_id;
                    // print_r($main_budget_info->getErrors());
                    $exp_detail->save(false);
                }


                unlink($file);


                Yii::$app->session->setFlash('success', 'Expense(s) Uploaded Successfully ');
                $this->redirect(['site/finance']);
            } else {
                // var_dump('exit on failure');
                // return;
                Yii::$app->session->setFlash('danger', 'Expense(s) Not Uploaded ');
                $this->redirect(['site/finance']);
            }
            // var_dump('faaa');
            // return;


        }
    }

    // uploading bulky departmental budget
    public function actionDepartmentBudgetUpload()
    {
        $department_budget = new Budget(); //creating object

        if ($department_budget->load(yii::$app->request->post())) {
            // extracting ModelType or Name (which is incoming)
            $modelRendered = array_keys(yii::$app->request->post());
            $model_keys = yii::$app->request->post();
            // var_dump($modelRendered[1]);
            // return;
            $modalTypeValue = $modelRendered[0];

            // var_dump(($_FILES));
            // return;
            // pick file on upload
            $file_content = UploadedFile::getInstance($department_budget, 'department_budget_upload');

            // initialise budget  ====  organisational budget 
            $org_budget_id = $model_keys[$modalTypeValue]['main_budget_id'];
            // department _id 
            $depart_id = $model_keys[$modalTypeValue]['department_id'];
            // align uploadded instance to  the modal
            $department_budget->department_budget_upload = $file_content;
            // var_dump($file_content);
            // return;
            // check upload availability 
            if ($department_budget->department_budget_upload) {
                // uploading file to uploads directory
                $department_budget->department_budget_upload->saveAs('uploads/' . $department_budget->department_budget_upload->baseName . '.' . $department_budget->department_budget_upload->extension);

                // attain  file uploaded path
                // $file = 'web/images/main_budget.xls';
                // /var/www/html/requixa/uploads
                $file_path_link = 'uploads/' . $department_budget->department_budget_upload->baseName . '.' . $department_budget->department_budget_upload->extension;

                // break down to upload budget dynamically
                // $file = (string)$file_path_link;
                $file = (string)$file_path_link;
                // $file = 'uploads/main_budget_try.xls';

                // var_dump($file);
                // return;

                try {

                    // $inputFileType

                    $inputFileType = IOFactory::identify($file);
                    $objReader = IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($file);
                } catch (Exception $e) {
                    // die('Error');
                    return yii::$app->session->setFlash('danger', 'File not uploaded');
                }


                $sheet = $objPHPExcel->getSheet(0);
                $heighestRow = $sheet->getHighestRow();
                $heightsCol = $sheet->getHighestColumn();

                for ($row = 1; $row <= $heighestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $heightsCol . $row, NULL, TRUE, FALSE);
                    if ($row == 1) {
                        continue;
                    }

                    $dept_budget = new Budget();
                    $dept_budget->name = $rowData[0][0];
                    $dept_budget->budget_category = $rowData[0][1];
                    $dept_budget->budget_warning_limit = $rowData[0][2];
                    $dept_budget->budget_amount_limit = $rowData[0][3];
                    $dept_budget->start_date = date('Y-m-d', strtotime($rowData[0][4]));
                    $dept_budget->end_date = date('Y-m-d', strtotime($rowData[0][5]));
                    $dept_budget->main_budget_id = $org_budget_id;
                    $dept_budget->department_id = $depart_id;
                    $dept_budget->created_by = Helpers::LoggedInUserData()->id;
                    $dept_budget->organisation_id = Helpers::LoggedInUserData()->organisation_id;
                    // print_r($main_budget_info->getErrors());
                    $dept_budget->save(false);
                }


                unlink($file);


                Yii::$app->session->setFlash('success', 'Department Uploaded Successfully ');
                $this->redirect(['site/finance']);
            } else {
                // var_dump('exit on failure');
                // return;
                Yii::$app->session->setFlash('danger', 'Department Not Uploaded ');
                $this->redirect(['site/finance']);
            }
            // var_dump('faaa');
            // return;


        }
    }
}
