<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidate".
 *
 * @property int $id
 * @property string|null $student_id
 * @property string|null $position_id
 * @property string|null $created_by
 * @property string|null $created_at
 * @property string|null $image
 */
class Candidate extends \yii\db\ActiveRecord
{
public $full_name,$name,$position_name,$code,$image,$candidate_no;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'candidate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['student_id', 'position_id'], 'required'],
            [['student_id', 'position_id', 'created_by', 'created_at'], 'default', 'value' => null],
            [['student_id', 'position_id', 'created_by', 'created_at'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'student_id' => Yii::t('app', 'Student ID'),
            'position_id' => Yii::t('app', 'Position ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'image' => Yii::t('app', 'Image'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

}
