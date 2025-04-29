<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Position;

/**
 * PositionSearch represents the model behind the search form of `app\models\Position`.
 */
class PositionSearch extends Position
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'description', 'code', 'created_by', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Position::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }

    public function getData()
    {
        return $this->find()->asArray()->all();
        // TODO: Implement getData() method.
    }

    public function exportColumns()
    {
        return [
            'description'=>'Description',
            'created_by'=>[
            'label'=>'Created By',
            'format'=>function($model){
                return User::findOne($model)->firstname ?? '(not found)';
            }
        ], 'created_at'=>[
            'label'=>'Created At', 'format'=>function($model){
            return date('d-m-Y', $model);
            }
        ]
        ];
    }

    public function searchFields()
    {
        // TODO: Implement searchFields() method.
    }

    public function tableColumns()
    {
        return ['name'=>'Name', 'description'=>'Description', 'created_by'=>'Created by', 'created_at'=>['label'=>'Created at',
            'format'=>function($model){
            return  $model ? date('d-m-Y', $model->created_at) : '(not set)';
            }]];
    }
}
