<?php

namespace app\models;

/**
 * This is the model class for table "bill_tariff".
 *
 * @property int $id
 * @property string $name
 * @property int $day_cost
 * @property int $start_days
 * @property int $city_id
 * @property City $city
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
            [['name', 'day_cost', 'start_days', 'city_id'], 'required'],
            [['day_cost', 'start_days', 'city_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
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
            'start_days' => 'Пороговое число дней',
            'city_id' => 'Город',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBillPayments()
    {
        return $this->hasMany(BillPayment::className(), ['tariff_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * Возвоащает подходящий тариф по количеству выборанных суток
     * @param $count
     * @return BillTariff|null
     */
    static public function findTariffByDaysCount($count)
    {
        $matchTariff = null;

        /**
         * @var BillTariff $tariff
         */
        foreach(BillTariff::find()->where(['city_id' => 1])->orderBy(['start_days'=>SORT_ASC])->all() as $tariff) {
            if($count >= $tariff->start_days) {
                $matchTariff = $tariff;
            }
        }

        return $matchTariff;
    }
}
