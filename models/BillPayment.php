<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_payment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $created_at
 * @property integer $amount
 * @property integer $tariff_id
 * @property integer $days
 *
 * @property BillTariff $tariff
 * @property User $user
 */
class BillPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'amount', 'tariff_id', 'days'], 'required'],
            [['user_id', 'amount', 'tariff_id', 'days'], 'integer'],
            [['created_at'], 'safe'],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillTariff::className(), 'targetAttribute' => ['tariff_id' => 'id']],
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
            'created_at' => 'Created At',
            'amount' => 'Amount',
            'tariff_id' => 'Tariff ID',
            'days' => 'Days',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(BillTariff::className(), ['id' => 'tariff_id']);
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
                $this->created_at = date('Y-m-d H:i:s');
            }
            return false;
        }
        return false;
    }
}
