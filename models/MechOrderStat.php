<?php

namespace app\models;

/**
 * This is the model class for table "mech_order_stat".
 *
 * @property string $login
 * @property string $name
 * @property string $birth_date
 * @property int $city_id
 * @property int $orders_count
 * @property int $category_id
 * @property string $first_action
 * @property string $last_action
 */
class MechOrderStat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mech_order_stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['birth_date', 'first_action', 'last_action'], 'safe'],
            [['orders_count', 'category_id', 'city_id'], 'integer'],
            [['login', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Телефон',
            'name' => 'Имя',
            'birth_date' => 'Дата рождения',
            'city_id' => 'Город',
            'orders_count' => 'Количество заказов',
            'category_id' => 'Категория',
            'first_action' => 'Первая активность',
            'last_action' => 'Последняя активность',
        ];
    }

    public static function primaryKey()
    {
        return ['login'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
