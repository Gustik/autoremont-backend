<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "call".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $client_id
 * @property integer $mech_id
 * @property integer $order_id
 * @property integer $client_accept
 * @property integer $mech_accept
 * @property integer $is_active
 *
 * @property User $client
 * @property User $mech
 * @property Order $order
 */
class Call extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'mech_id', 'order_id'], 'required'],
            [['created_at', 'updated_at', 'client_accept', 'mech_accept'], 'safe'],
            [['client_id', 'mech_id', 'order_id', 'client_accept', 'mech_accept', 'is_active'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'client_id' => 'Идентификатор клиента',
            'mech_id' => 'Идентификатор механика',
            'order_id' => 'Идентификатор заказа',
            'client_accept' => 'Статус договоренности со стороны клиента',
            'mech_accept' => 'Статус договоренности со стороны механика',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCleint()
    {
        return $this->hasOne(User::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMech()
    {
        return $this->hasOne(User::className(), ['id' => 'mech_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}