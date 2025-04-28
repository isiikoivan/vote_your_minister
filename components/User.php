<?php

namespace app\models;

use app\components\Generics\CrossHelper;
use app\modules\Admissions\models\Students;
use app\modules\CampusSetup\models\Departments;
use app\modules\Permissions\models\AuthGroups;
use app\modules\Permissions\models\AuthRoles;
use app\modules\RBAC\models\AuthAssignment;
use app\modules\RBAC\models\AuthItem;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int|null $university_id
 * @property int|null $campus_id
 * @property int|null $faculty_id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string|null $password_expiry_date
 * @property bool|null $mfa_enabled
 * @property string|null $mfa_secret
 * @property bool|null $locked
 * @property string $first_name
 * @property string $last_name
 * @property int $role
 * @property string $phone
 * @property string|null $address
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property bool|null $is_active
 * @property bool|null $is_remote_student
 * @property string|null $timezone
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property string|null $authkey
 * @property string|null $accesstoken
 *
 * @property AcademicYears[] $academicYears
 * @property Announcements[] $announcements
 * @property Announcements[] $announcements0
 * @property Assignments[] $assignments
 * @property Attendance[] $attendances
 * @property AuditLogs[] $auditLogs
 * @property BookLoans[] $bookLoans
 * @property BookLoans[] $bookLoans0
 * @property Campuses[] $campuses
 * @property Courses[] $courses
 * @property Departments[] $departments
 * @property Departments[] $departments0
 * @property Enrollments[] $enrollments
 * @property EventRegistrations[] $eventRegistrations
 * @property Events[] $events
 * @property ExamResults[] $examResults
 * @property Exams[] $exams
 * @property Faculties[] $faculties
 * @property Faculties[] $faculties0
 * @property FacultyMembers[] $facultyMembers
 * @property FacultyMembers[] $facultyMembers0
 * @property FeeTypes[] $feeTypes
 * @property Fees[] $fees
 * @property LibraryBooks[] $libraryBooks
 * @property OnlineResources[] $onlineResources
 * @property OnlineSupportSessions[] $onlineSupportSessions
 * @property Payments[] $payments
 * @property Permissions[] $permissions
 * @property Programs[] $programs
 * @property Registrations[] $registrations
 * @property RemoteLearningMaterials[] $remoteLearningMaterials
 * @property ResourceBookings[] $resourceBookings
 * @property ResourceBookings[] $resourceBookings0
 * @property Resources[] $resources
 * @property Semesters[] $semesters
 * @property StudentAssignments[] $studentAssignments
 * @property Students[] $students
 * @property Students[] $students0
 * @property VirtualClassrooms[] $virtualClassrooms
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public $password_reset_token ='';
    const STATUS_INACTIVE = false;
    const STATUS_ACTIVE = true;
    public $groupId = '';
    public $enable = true;

    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {


        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()
            ->where(['email' => $username,'is_active'=>true])
            ->orWhere(['phone' => $username])
            ->one();

    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'accesstoken' => $token,
            'is_active' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire']; // Set this in params.php (e.g., 3600 * 24)
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {

        return [
            [['university_id', 'campus_id', 'faculty_id'], 'default', 'value' => null],
            [['university_id', 'campus_id', 'role', 'faculty_id'], 'integer'],
            [['username', 'email', 'password_hash', 'first_name', 'last_name', 'phone'], 'required'],
            [['password_expiry_date', 'created_at','role', 'date_of_birth', 'updated_at'], 'safe'],
            [['mfa_enabled', 'locked', 'is_active', 'is_remote_student'], 'boolean'],
            [['address'], 'string'],
            [['username', 'first_name', 'groupId', 'last_name', 'timezone'], 'string', 'max' => 50],
            [['email', 'authkey', 'accesstoken'], 'string', 'max' => 100],
            [['password_hash', 'mfa_secret'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['gender'], 'string', 'max' => 10],
            [['email'], 'unique',
                'message' => 'This email address is already registered.',
                'when' => function ($model) {
                    return $model->isNewRecord || $model->isAttributeChanged('email');
                }],
//            [['username'], '',
//                'message' => 'This username is already taken.',
//                'when' => function($model) {
//                    return $model->isNewRecord || $model->isAttributeChanged('username');
//                }],
            [['email'], 'email', 'message' => 'Please enter a valid email address'],
            ['password_hash', 'match',
                'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])/',
                'message' => 'Password must contain at least 8 characters including uppercase, lowercase, number and special character'],
            [['phone'], 'match', 'pattern' => '/^\+?[0-9]{10,15}$/', 'message' => 'Phone number must be between 10-15 digits']
        ];
    }

    public function getData()
    {
        return $this->find()->asArray(true)->all();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'university_id' => 'University ID',
            'campus_id' => 'Campus ID',
            'groupId' => 'User Group',
            'faculty_id' => 'Faculty ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'password_expiry_date' => 'Password Expiry Date',
            'mfa_enabled' => 'Mfa Enabled',
            'mfa_secret' => 'Mfa Secret',
            'locked' => 'Locked',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'role' => 'Role',
            'phone' => 'Phone',
            'address' => 'Address',
            'date_of_birth' => 'Date Of Birth',
            'gender' => 'Gender',
            'is_active' => 'Is Active',
            'is_remote_student' => 'Is Remote Student',
            'timezone' => 'Timezone',
            'created_at' => 'Date Created',
            'updated_at' => 'Date Updated',
            'created_by' => 'Created By',
            'authkey' => 'Authkey',
            'accesstoken' => 'Accesstoken',
        ];
    }

    public function group()
    {
        $query = AuthGroups::find();
        !yii::$app->sec->hasRole("BackOfficeSuperAdmin") ?
            $query->where(['universityid' => Yii::$app->user->identity->university_id])
            :
            $query;
        return $query ? ArrayHelper::map($query->all(), 'id', 'groupname') : [];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->username)) {
                $baseName = preg_replace('/[^a-zA-Z0-9]/', '', $this->first_name . $this->last_name);
                $this->username = strtolower($baseName) . '-' . substr(uniqid(), -6);
            }

            $this->password_hash = "@Default@123";
        }

        return parent::beforeValidate();
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Always update these fields
            $this->updated_at = date('Y-m-d H:i:s');

            // For new records
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->is_active = true;
                // Set university related fields if user is logged in
                if (!empty(Yii::$app->user->identity)) {
                    $this->university_id = $this->university_id ?: Yii::$app->user->identity->university_id;
                    $this->campus_id = $this->campus_id ?: Yii::$app->user->identity->campus_id;
                    $this->faculty_id = $this->faculty_id ?: Yii::$app->user->identity->faculty_id;
                }

                // Set default role if empty
                $this->role = $this->role ?: 0;
                // Always hash the password for new records
                if(empty($this->password_hash)) {
                    $this->password_hash = "@Default@123";

                }
                if (!empty($this->password_hash)) {
                    $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
                }
            }
            return true;
        }else{
            return false;
        }
    }
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['accesstoken']);
        unset($fields['password_expiry_date']);
        unset($fields['tmezone']);

        $fields['first_name'] = function ($model) {
            return ucfirst($model->first_name);
        };
        $fields['last_name'] = function ($model) {
            return ucfirst($model->last_name);
        };
        $fields['created_at'] = function ($model) {
            return date('Y-m-d H:i:s', strtotime($model->created_at));
        };
        $fields['updated_at'] = function ($model) {
            return date('Y-m-d H:i:s', strtotime($model->updated_at));
        };
        $fields['phone'] = function ($model) {
            return number_format($model->phone);
        };
        return $fields;
    }

    public function generateRandomPassword()
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '@$!%*?&';

        $password = '';

        // Add at least one character from each required set
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];

        // Add random characters until password length is 12
        $all_characters = $lowercase . $uppercase . $numbers . $special;
        while (strlen($password) < 12) {
            $password .= $all_characters[rand(0, strlen($all_characters) - 1)];
        }
        // Shuffle the password to make it more random
        $password = str_shuffle($password);
        return $password;
    }



    public function Create($post)
    {
        if ($this->load($post)) {
            if ($this->save(false)) {
                return true;
            } else {
                return null;
            }
        }
    }

    /**
     * Gets query for [[Departments0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments0()
    {
        return $this->hasMany(Departments::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ExamResults]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExamResults()
    {
        return $this->hasMany(ExamResults::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Exams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExams()
    {
        return $this->hasMany(Exams::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Faculties0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaculties0()
    {
        return $this->hasMany(Faculties::class, ['created_by' => 'id']);
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
        // return $this->authkey;
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // return $this->authkey === $authKey;
        return null;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function universities()
    {
        $university = Universities::find();
        !yii::$app->sec->hasRole("BackOfficeSuperAdmin") ?
            $university->where(['id' => Yii::$app->user->identity->university_id])
            : $university;
        return ArrayHelper::map($university->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }
    public function campuses()
    {
        $university = Campuses::find();
        !yii::$app->sec->hasRole("BackOfficeSuperAdmin") ?
            $university->where(['id' => CrossHelper::user()->campus_id])
            : $university;
        return ArrayHelper::map($university->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    public function faculty()
    {
        $fac = Faculties::find();
        if (Yii::$app->user->isGuest) {
            return;
        }
        !Yii::$app->sec->hasRole("BackOfficeSuperAdmin") ?
            $fac->where(['university_id' => Yii::$app->user->identity->university_id]) :
            $fac;
        return ArrayHelper::map($fac->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    public function roles()
    {
        $fac = AuthRoles::find();
        if (Yii::$app->user->isGuest) {
            return;
        }
        !Yii::$app->sec->hasRole("BackOfficeSuperAdmin") ?
            $fac->where(['university_id' => Yii::$app->user->identity->university_id]) :
            $fac;
        return ArrayHelper::map($fac->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    public function campus()
    {
        $camp = Campuses::find();
        !Yii::$app->sec->hasRole("BackOfficeSuperAdmin") ?
            $camp->where(['university_id' => Yii::$app->user->identity->university_id])
                ->andFilterWhere(['faculty_id' => Yii::$app->user->identity->faculty_id])
                ->andFilterWhere(['id' => Yii::$app->user->identity->campus_id])
            :
            $camp;
        return ArrayHelper::map($camp->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    }

    public function role()
    {
        return $this->hasOne(AuthItem::class, ['id' => 'created_by']);
    }

    public function getUserRole()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id']);
    }

    public function studentLogin()
    {
        return [];
    }

    public function isSuper(): bool
    {
        return AuthRoles::find()->select('name')->where(['id' => yii::$app->user->identity->role])->scalar() === 'BackOfficeSuperAdmin';
    }

    public function university()
    {
        return (Universities::findOne(['id' => $this->university_id]))->name ?? null;
    }

    public function campusName($c=null)
    {
        return (Campuses::findOne(['id' => $this->campus_id ?? $c ]))->name ?? null;
    }

    public function roleName()
    {
        return (AuthRoles::findOne(['id' => $this->role]))->name ?? null;
    }

    public function universityName($id)
    {
        $uni = Universities::findOne(['id' => $id]);
        return $uni ? $uni->name : '-';
    }

    public function facultyName($id)
    {
        $fac = Faculties::findOne(['id' => $id]);
        return $fac ? $fac->name : '-';
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert || (isset($changedAttributes['enable']) && $this->enable==1)) {
            if(!Yii::$app instanceof \yii\console\Application) {
                $confirmationUrl = Yii::$app->urlManager->createAbsoluteUrl([
                'landing/confirm',
                'token' => uniqid($this->id . "-CFM")
            ]);

            $params = [
                'user' => $this,
                'password' => '@Default@123',
                'confirmationUrl' => $confirmationUrl,
                'appName' => Yii::$app->name ?? 'BSU',
                'supportEmail' => 'support@servicecops.com',
                'from' => 'officetool@servicecops.com'
            ];

//        if((empty($this->id) ||  $this->role!=0) || !$this->enable || $this->isNewRecord){
                if ( $this->role != 0) {
                    yii::$app->emailService->sendTemplateEmail(
                        $this->email,
                        'Welcome to ' . Yii::$app->name . ' - Account Details',
                        'confirmation',
                        $params);
                }
            }

        }

    }

    public function afterDelete()
    {
        parent::afterDelete();
        $staff = Staff::findOne(['user_id' => $this->id]);
        if (!empty($staff)) {
            $staff->delete();
        }
        $student = Students::findOne(['user_id' => $this->id]);
        if (!empty($student)) {
            $student->delete();
        }

        return true;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->accesstoken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->accesstoken = null;
    }

    public function setPassword($password){
        $this->password_hash=Yii::$app->security->generatePasswordHash($password);
    }


}
