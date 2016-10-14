<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_tariff".
 *
 * @property integer $id
 * @property string $name
 * @property integer $day_cost
 *
 * @property BillPayment[] $billPayments
 */
class BillTariff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_tariff';
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'day_cost' => 'Day Cost',
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
