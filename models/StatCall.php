<?php

namespace app\models;

/**
 * This is the model class for table "stat".
 *
 * @property int $id
 * @property string $created_at
 * @property string $from
 * @property string $to
 * @property int $cat
 */
class StatCall extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_call';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['from', 'to', 'cat'], 'required'],
            [['from', 'to'], 'string'],
            [['cat'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Время',
            'from' => 'Кто звонил',
            'to' => 'Компания',
            'cat' => 'Категория',
        ];
    }

    public function getCompanyName()
    {
        $company = Company::find()->where(['phone' => $this->to])->one();
        if ($company) {
            $company = $company->name;
        } else {
            $company = '';
        }

        return "$company ($this->to)";
    }

    public function getCompanyCategory()
    {
        return Company::$categories[$this->cat];
    }
}
