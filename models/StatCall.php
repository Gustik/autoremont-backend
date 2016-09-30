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
 * @property integer $cat
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
            [['from', 'to', 'cat'], 'required'],
            [['from', 'to'], 'string'],
            [['cat'], 'integer']
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
            'from' => 'Кто звонил',
            'to' => 'Компания',
            'cat' => 'Категория'
        ];
    }

    public function getCompanyName()
    {
        return Company::find()->where(['phone' => $this->to])->one()->name." ($this->to)";
    }

    public function getCompanyCategory()
    {
        return Company::$categories[$this->cat];
    }
}
