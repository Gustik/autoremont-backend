<?php

namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "stat".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $from
 * @property string $to
 */
class StatCall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stat_call';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['from', 'to'], 'required'],
            [['from', 'to'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Время',
            'from' => 'От',
            'to' => 'К'
        ];
    }
}