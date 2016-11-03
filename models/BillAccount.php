<?php

namespace app\models;

use yii\db\Expression;

/**
 * This is the model class for table "bill_account".
 *
 * @property int $id
 * @property int $user_id
 * @property int $days
 * @property int $processed_at
 * @property User $user
 */
class BillAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_account';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->processed_at = new Expression('NOW()');

                return true;
            }

            return true;
        }

        return false;
    }
}
