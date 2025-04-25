<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * CandidateSearch represents the model behind the search form of `app\models\Candidate`.
 */
class CandidateSearch extends Candidate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['student_id', 'position_id', 'created_by', 'created_at'], 'safe'],
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

        $query = Candidate::find()
            ->select([
                'c.*',
                "concat(u.firstname,' ' ,u.lastname) as full_name",
                'p.code',
                'p.description',
                'p.name'
            ])
            ->from('candidate as c')
            ->innerJoin('user AS u', 'u.id = c.student_id')
            ->innerJoin('position AS p', 'p.id = c.position_id');
//            ->asArray(); // Very important

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'c.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'c.student_id', $this->student_id])
            ->andFilterWhere(['like', 'c.position_id', $this->position_id])
            ->andFilterWhere(['like', 'c.created_by', $this->created_by])
            ->andFilterWhere(['like', 'c.created_at', $this->created_at]);

        return $dataProvider;
    }
}
