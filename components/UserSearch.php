<?php

namespace app\models;

use app\components\Generics\CrossHelper;
use app\modules\Permissions\models\AuthRoles;
use yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;


class UserSearch extends User
{


    public function rules()
    {
        return [
            [['id', 'username', 'first_name', 'last_name', 'email', 'campus_id', 'created_at', 'university_id', 'role', 'is_active'], 'safe']
        ];
    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search($params)
    {
        $this->load($params);

        $ident = yii::$app->user->isGuest ? null : yii::$app->user->identity;
        yii::$app->session->remove("data");
        $this->load($params);

        $query = User::find()
            ->select(['users.*', 'auth_roles.name as role_name'])
            ->leftJoin('auth_roles', 'users.role = auth_roles.id');

        $rid = AuthRoles::find()->andFilterWhere(['ilike', 'name', 'student'])->one();
        if($rid){
                $query->andFilterWhere(['<>','role',$rid->id]);
            }
        if (!yii::$app->sec->hasRole("BackOfficeSuperAdmin")) {
            $query->andFilterWhere(['users.university_id' => $ident->university_id])
                ->andFilterWhere(['users.campus_id' => $ident->campus_id]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($params['UserSearch']) && isset($params['UserSearch'])) {
            $query->andFilterWhere([
                'users.id' => $this->id,
                'users.created_at' => $this->created_at,
                'users.university_id' => $this->university_id,
                'users.campus_id' => $this->campus_id,
                'users.is_active' => $this->is_active,
                'users.role' => $this->role,
            ]);

            $query->andFilterWhere(['or',

                ['like', 'LOWER(users.email)', strtolower($this->email)],
            ]);

        }

        yii::$app->session->set("data", $dataProvider->getModels());
        return $dataProvider;
    }

    /*
    This is a method that queries data to be exported
    */
    public function getData()
    {
        $data = yii::$app->session->get("data");
        return $data ?? $this->find()
            ->select(['users.*', 'auth_roles.name as role_name'])
            ->innerJoin('auth_roles', 'users.role = auth_roles.id')->asArray()->all();
    }

    /*
    this is a method that is used to return the columns that will be used when generating exported document
    the key defines the value that is used for iterating through data to be exported and the values is used to populated the header labels
    */

    public function exportColumns()
    {
        return [
            'email' => 'Email Address',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone Number',
            'address' => 'Address',
            'gender' => 'Gender',
            'created_at' => [
                'label' => 'Date Created',
                'format' => function ($value) {
                    return $value ? Yii::$app->formatter->asDate($value, 'php:Y-m-d') : '';
                }
            ]
        ];
    }

    public function tableColumns()
    {
        $col = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email Address',
            'role' => ['label' => 'Role',
                'format' => function ($value) {
                    return AuthRoles::findOne(['id' => $value])->name ?? '(Not set)';
                }
            ],
            'university_id' => [
                'label' => 'University',
                'format' => function ($value) {
                    return $this->universityName($value) ?? '-';
                }
            ],
            'campus_id' => [
                'label' => 'Campus',
                'format' => function ($c) {
                    return $this->campusName($c) ?? '-';
                }
            ],
            'is_active' => [
                'label' => 'Status',
                'format' => function ($data) {
                    return $data === 0 ? "<span class='badge bg-danger-subtle text-danger'>Inactive</span>" : "<span class='badge bg-success-subtle text-success'>Active</span>";
                }
            ],
            'created_at' => [
                'label' => 'Date Created',
                'format' => function ($value) {
                    return $value ? Yii::$app->formatter->asDate($value, 'php:Y-m-d') : '';
                }
            ],
            'action' => [
                'label' => 'Action',
                'actions' => [
                    function ($data) {
                        return Html::a('<i class="fa fa-shield"></i>', ['grant-access', 'id' => $data->id], ['class' => 'btn btn-outline-primary btn-sm', 'title' => 'Grant access']);
                    },
                    function ($data) {
                        return Html::a('<i class="fa fa-edit"></i>', ['update', 'id' => $data->id], ['class' => 'btn btn-outline-primary btn-sm', 'title' => 'Update details']);
                    },
                    function ($data) {
                        return Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $data->id],
                            [
                                'class' => 'btn btn-outline-danger btn-sm',
                                'title' => 'Drop account',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post'
                                ]
                            ]);
                    },
                ]
            ]
        ];

        if (yii::$app->sec->hasRole("BackOfficeSuperAdmin")) {
            unset($col['campus_id']);
        }

        return $col;
    }

    /*
    This is a method that defines the field that will be returned/ rendered for searching through the data
    */
    public function searchFields()
    {
        $fields = [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Search by email',
                'name' => 'email',
            ],
            'role' => [
                'type' => 'select',
                'placeholder' => 'Search by role',
                'name' => 'role',
                'options' => $this->roles()
            ],
            'university_id' => [
                'type' => 'select',
                'placeholder' => 'Search by University',
                'name' => 'university_id',
                'options' => $this->universities()
            ],
            'campus_id' => [
                'type' => 'select',
                'placeholder' => 'Search by campus',
                'name' => 'campus_id',
                'options' => $this->campuses()
            ],
            'is_active' => [
                'type' => 'select',
                'placeholder' => 'Search by status',
                'name' => 'is_active',
                'options' => [
                    '1' => 'Active',
                    '0' => 'Inactive'
                ]
            ]


        ];

        if (!yii::$app->user->isGuest && !yii::$app->sec->hasRole("BackOfficeSuperAdmin")) {
            unset($fields['university_id']);
        }

        if(CrossHelper::user()->campus_id){
            unset($fields['campus_id']);
            unset($fields['university_id']);
        }

        return $fields;
    }
}
