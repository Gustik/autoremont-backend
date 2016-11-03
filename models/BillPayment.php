<?php

namespace app\models;

use yii\db\Expression;

/**
 * This is the model class for table "bill_payment".
 *
 * @property int $id
 * @property int $user_id
 * @property string $created_at
 * @property int $amount
 * @property int $tariff_id
 * @property int $days
 * @property BillTariff $tariff
 * @property User $user
 */
class BillPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'tariff_id', 'days'], 'required'],
            [['user_id', 'amount', 'tariff_id', 'days'], 'integer'],
            [['created_at', 'amount'], 'safe'],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillTariff::className(), 'targetAttribute' => ['tariff_id' => 'id']],
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
            'user_id' => 'ID пользователя',
            'created_at' => 'Создан',
            'amount' => 'Сумма',
            'tariff_id' => 'Тариф',
            'days' => 'Сутки',
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
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = new Expression('NOW()');

                return true;
            }

            return true;
        }

        return false;
    }
}
