<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "position".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $code
 * @property string|null $created_by
 * @property string|null $created_at
 */
class Position extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'position';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'code'], 'required'],
            [['name', 'description', 'code', 'created_by', 'created_at'], 'default', 'value' => null],
            [['name', 'description', 'code', 'created_by', 'created_at'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'code' => Yii::t('app', 'Code'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    public function TableColumns()
    {
        return [];
        // TODO: Implement TableColumns() method.
    }

}
