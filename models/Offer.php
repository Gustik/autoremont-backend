<?php

namespace app\models;

/**
 * This is the model class for table "offer".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $text
 * @property int $order_id
 * @property int $author_id
 * @property int $is_active
 * @property User $author
 * @property Order $order
 * @property bool is_call
 */
class Offer extends Model
{
    public $reviewed = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-create'] = ['text', 'order_id'];
        $scenarios['api-update'] = ['text'];
        $scenarios['api-view'] = ['id', 'text', 'created_at', 'updated_at', 'author_id', 'order_id', 'author', 'reviewed'];

        return $scenarios;
    }

    public function getAttributes($names = null, $except = [])
    {
        $values = parent::getAttributes($names, $except);
        $values['reviewed'] = $this->reviewed;

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'order_id', 'author_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['text'], 'string'],
            [['order_id', 'author_id', 'is_active', 'is_call'], 'integer'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'text' => 'Текст',
            'order_id' => 'Заявка',
            'author_id' => 'Автор',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public static function findProduce($orderID, $userID)
    {
        if ($offer = self::findOne(['is_active' => true, 'order_id' => $orderID, 'author_id' => $userID])) {
            $offer->setScenario('api-update');
        } else {
            $offer = new self();
            $offer->setScenario('api-create');
            $offer->author_id = $userID;
            $offer->order_id = $orderID;
        }

        return $offer;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!array_key_exists('is_new', $this->dirtyAttributes)) {
                $this->is_new = true;
            }

            return true;
        }

        return false;
    }
}
