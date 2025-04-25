<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $password
 * @property string|null $email
 * @property string|null $course
 * @property string|null $year
 * @property string|null $regNo
 * @property string|null $createdby
 * @property string|null $createdat
 */
class Student extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'password', 'email', 'course', 'year', 'regNo', 'createdby', 'createdat'], 'default', 'value' => null],
            [['firstname', 'lastname', 'password', 'email', 'course', 'year', 'regNo', 'createdby', 'createdat'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
            'course' => Yii::t('app', 'Course'),
            'year' => Yii::t('app', 'Year'),
            'regNo' => Yii::t('app', 'Reg No'),
            'createdby' => Yii::t('app', 'Createdby'),
            'createdat' => Yii::t('app', 'Createdat'),
        ];
    }

}

//class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
//{
//    /**
//     * {@inheritdoc}
//     */
//    public static function tableName()
//    {
//        return 'web_console_users';
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function rules()
//    {
//        return [
//            [['username', 'first_name', 'mobile_number', 'email_address', 'user_level'], 'required'],
//            [['username', 'first_name', 'mobile_number', 'email_address', 'password', 'user_level', 'user_permissions', 'last_name', 'middle_name'], 'string'],
//            [['incorrect_access_count', 'school_id', 'bank_id'], 'default', 'value' => null],
//            [['incorrect_access_count', 'school_id', 'bank_id'], 'integer'],
//            [['date_created', 'date_updated', 'password_expiry_date'], 'safe'],
//            [['locked'], 'boolean'],
//            [['password_reset_token', 'auth_key'], 'string', 'max' => 2044],
//            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolInformation::className(), 'targetAttribute' => ['school_id' => 'id']],
//        ];
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'ID',
//            'username' => 'Username',
//            'first_name' => 'First Name',
//            'mobile_number' => 'Mobile Number',
//            'email_address' => 'Email Address',
//            'incorrect_access_count' => 'Incorrect Access Count',
//            'password' => 'Password',
//            'date_created' => 'Date Created',
//            'locked' => 'Locked',
//            'user_level' => 'User Level',
//            'school_id' => 'School ID',
//            'user_permissions' => 'User Permissions',
//            'password_reset_token' => 'Password Reset Token',
//            'auth_key' => 'Auth Key',
//            'date_updated' => 'Date Updated',
//            'last_name' => 'Last Name',
//            'middle_name' => 'Middle Name',
//            'bank_id' => 'Bank ID',
//            'password_expiry_date' => 'Password Expiry Date',
//        ];
//    }
//
////implementing the identity interface
//    public static function findIdentity($id)
//    {
//        return static::findOne(['id' => $id]);
//    }
//
//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        return static::findOne(['access_token' => $token]);
//    }
//
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    public function getAuthKey()
//    {
//        return null;
//    }
//
//
//    public function validateAuthKey($auth_Key)
//    {
//        return null;
//    }
//
//    public static function findByUsername($username){
//        return static::findOne(['username' => strtolower($username)]);
//    }
//
//    public function validatePassword($password){
//
//        return Yii::$app->security->validatePassword($password, $this->password);
//    }
//
//
//
//
//}
