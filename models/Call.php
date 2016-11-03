<?php

namespace app\models;

/**
 * This is the model class for table "call".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $client_id
 * @property int $mech_id
 * @property int $order_id
 * @property int $client_accept
 * @property int $mech_accept
 * @property int $is_active
 * @property User $client
 * @property User $executor
 * @property Order $order
 */
class Call extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'call';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-view'] = ['id', 'client', 'executor'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'mech_id', 'order_id'], 'required'],
            [['created_at', 'updated_at', 'client_accept', 'mech_accept'], 'safe'],
            [['client_id', 'mech_id', 'order_id', 'client_accept', 'mech_accept', 'is_active'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function getClient()
    {
        return $this->hasOne(User::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
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

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $call = self::findOne(['order_id' => $this->order_id, 'mech_id' => $this->mech_id]);
                if ($call) {
                    $call->delete();
                }
            }

            return true;
        }

        return false;
    }
}
