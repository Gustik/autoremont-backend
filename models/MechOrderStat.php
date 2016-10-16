<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mech_order_stat".
 *
 * @property string $login
 * @property string $name
 * @property string $birth_date
 * @property integer $orders_count
 * @property integer $category_id
 * @property string $first_action
 * @property string $last_action
 */
class MechOrderStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mech_order_stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['birth_date', 'first_action', 'last_action'], 'safe'],
            [['orders_count', 'category_id'], 'integer'],
            [['login', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Телефон',
            'name' => 'Имя',
            'birth_date' => 'Дата рождения',
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
}
