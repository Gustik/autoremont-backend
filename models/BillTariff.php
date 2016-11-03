<?php

namespace app\models;

/**
 * This is the model class for table "bill_tariff".
 *
 * @property int $id
 * @property string $name
 * @property int $day_cost
 * @property BillPayment[] $billPayments
 */
class BillTariff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bill_tariff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'day_cost'], 'required'],
            [['day_cost'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Тариф',
            'day_cost' => 'Стоимость за сутки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillPayments()
    {
        return $this->hasMany(BillPayment::className(), ['tariff_id' => 'id']);
    }
}
