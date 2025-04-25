<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ballot;
use yii\db\Query;

/**
 * BallotSearch represents the model behind the search form of `app\models\Ballot`.
 */
class BallotSearch extends Ballot
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'candidate_id', 'position_id','voter_id'], 'integer'],
            [['vote', 'created_at'], 'safe'],
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

//        select b.*, CONCAT(u.firstname ,' ',u.lastname) as full_name, sum(vote)as votes,p.name from ballot b
//inner join candidate c on c.id = b.candidate_id
//inner join `position` p on p.id = b.position_id
//inner join `user` u on u.id = c.student_id
//GROUP  by b.candidate_id ;
//        $query = Ballot::find()
//            ->select([
//                'b.*',
//                "CONCAT(u.firstname,' ' ,u.lastname) as full_name",
//                'SUM(vote) as votes',
//                'p.description',
//                'p.name'
//            ])
//            ->from('ballot As b ')
//            ->innerJoin(['candidate As c ', 'c.id = b.candidate_id'])
//            ->innerJoin(['position AS p', 'p.id = b.position_id'])
//            ->innerJoin(['user AS u', 'u.id = c.student_id']);
////            ->asArray(); // Very important
//        $query->groupBy('b.candidate_id');

        // add conditions that should always apply here

//        $query = Ballot::find();

        // add conditions that should always apply here

        $query = Ballot::find()
            ->select([
                'b.*',
                "CONCAT(u.firstname, ' ', u.lastname) AS full_name",
                'SUM(vote) AS votes',
                'p.description',
                'p.name as position',
            ])
            ->from('ballot b')
            ->innerJoin('candidate c', 'c.id = b.candidate_id')
            ->innerJoin('position p', 'p.id = b.position_id')
            ->innerJoin('user u', 'u.id = c.student_id')
            ->groupBy('b.candidate_id');
//            ->asArray(); // Uncomment this to return raw arrays


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
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'position_id' => $this->position_id,
        ]);

        $query->andFilterWhere(['like', 'vote', $this->vote])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }

    public function searchCandidate($params, $formName = null)
    {

//        $query = Candidate::find()
//            ->select([
//                'c.*',
//                "concat(u.firstname,' ' ,u.lastname) as full_name",
//                'u.image',
//                'p.id as position_id',
//                'p.code as code',
//                'p.description',
//                'p.name'
//            ])
//            ->from('candidate as c')
//            ->innerJoin('user AS u', 'u.id = c.student_id')
//            ->innerJoin('position AS p', 'p.id = c.position_id');
//  ->orderBy(['votes' => SORT_DESC])



        $query = Candidate::find()
            ->select([
                'c.*',
                'COUNT(c.student_id) AS candidate_no',
                'p.code',
                'p.description',
                'p.name'
            ])
            ->from('candidate c')
            ->innerJoin('user u', 'u.id = c.student_id')
            ->innerJoin('position p', 'p.id = c.position_id')
            ->groupBy('p.code') // Grouping by position code
//            ->asArray(); // Optional: use this if you want plain array results

        ->orderBy(['candidate_no' => SORT_DESC]);
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

//        $query->andFilterWhere(['like', 'c.student_id', $this->student_id])
           $query ->andFilterWhere(['like', 'c.position_id', $this->position_id])
//            ->andFilterWhere(['like', 'c.created_by', $this->created_by])
            ->andFilterWhere(['like', 'c.created_at', $this->created_at]);

        return $dataProvider;
    }
    public function searchViewCandidates($params, $formName = null,$id)
    {

//        $query = Candidate::find()
//            ->select([
//                'c.*',
//                "concat(u.firstname,' ' ,u.lastname) as full_name",
//                'u.image',
//                'p.id as position_id',
//                'p.code as code',
//                'p.description',
//                'p.name'
//            ])
//            ->from('candidate as c')
//            ->innerJoin('user AS u', 'u.id = c.student_id')
//            ->innerJoin('position AS p', 'p.id = c.position_id');


        $query = Candidate::find()
            ->select([
                'c.*',
                "CONCAT(u.firstname, ' ', u.lastname) AS full_name",
                'p.code',
                'p.description',
                'p.name'
            ])
            ->from('candidate c')
            ->innerJoin('user u', 'u.id = c.student_id')
            ->innerJoin('position p', 'p.id = c.position_id')
            ->where(['p.id' => $id]); // $id should be passed from your controller or context
//            ->asArray(); // Optional: enables raw data output


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

//        $query->andFilterWhere(['like', 'c.student_id', $this->student_id])
           $query ->andFilterWhere(['like', 'c.position_id', $this->position_id])
//            ->andFilterWhere(['like', 'c.created_by', $this->created_by])
            ->andFilterWhere(['like', 'c.created_at', $this->created_at]);

        return $dataProvider;
    }


    public function searchViewSingleCandidates($id)
    {
        $query = (new Query())
            ->select([
                'c.id as candidate_id',
                'c.student_id as user_id',
                'c.position_id as position_id',
                'u.*',
                'p.id as pos_id',
                'p.code as pos_code',
                'p.name as pos_name',
                'p.description as pos_description',
            ])
            ->from('candidate c')
            ->innerJoin('user u', 'u.id = c.student_id')
            ->innerJoin('position p', 'p.id = c.position_id')
            ->where(['student_id' => $id])// You can replace 2 with a dynamic variable
            ->one(); // Optional: returns results as an array

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}
