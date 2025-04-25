<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
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
 * @property int|null $createdat
 * @property string|null $role
 * @property string|null $status
 * @property string|null $image
 * @property string|null $username
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
public $image_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'password', 'email','course','year','regNo','role','status','image'], 'required'],
            [['firstname', 'lastname', 'password', 'email', 'course', 'year', 'regNo', 'createdby', 'createdat', 'role', 'status', 'image', 'username'], 'default', 'value' => null],
            [['createdat'], 'integer'],
            [['year'],'string', 'max'=>4],
            [['image'], 'string'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2 * 1024 * 1024],
            [['firstname', 'lastname', 'password', 'course', 'year', 'regNo', 'createdby', 'role', 'status', 'username'], 'string', 'max' => 100],
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
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'image' => Yii::t('app', 'Upload User Image'),
            'username' => Yii::t('app', 'Username'),
        ];
    }
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    /**
     * {@inheritdoc}
     */
//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }
//
//        return null;
//    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */

    public static function findByUsername($username){
        return static::findOne(['username' => strtolower($username)]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
//        return $this->authKey;
        return null;

    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
//        return $this->authKey === $authKey;
        return null;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */

    public function validatePassword($password){

        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
}
