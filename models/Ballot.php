<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ballot".
 *
 * @property int $id
 * @property int|null $candidate_id
 * @property int|null $position_id
 * @property int|null $voter_id
 * @property string|null $vote
 * @property string|null $created_at
 */
class Ballot extends \yii\db\ActiveRecord
{
public $full_name,$votes,$position,$candidate_no,$voter_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ballot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['candidate_id', 'position_id', 'vote','voter_id', 'created_at'], 'default', 'value' => null],
            [['candidate_id', 'position_id','voter_id'], 'integer'],
            [['vote', 'created_at'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'candidate_id' => Yii::t('app', 'Candidate '),
            'position_id' => Yii::t('app', 'Position '),
            'vote' => Yii::t('app', 'Vote'),
            'voter_id' => Yii::t('app', 'Voter'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

}
