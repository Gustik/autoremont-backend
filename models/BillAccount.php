<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "bill_account".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $days
 * @property integer $processed_at
 *
 * @property User $user
 */
class BillAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'days'], 'required'],
            [['user_id', 'days'], 'integer'],
            [['processed_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'days' => 'Days',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->processed_at = new Expression('NOW()');

                return true;
            }
            return false;
        }
        return false;
    }
}
