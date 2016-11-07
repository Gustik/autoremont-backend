<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int $mech_id
 * @property int $author_id
 * @property int $order_id
 * @property string $comment
 * @property int $rating
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $author
 * @property User $mech
 */
class Review extends Model
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-create'] = ['order_id', 'comment', 'mech_id', 'rating'];
        $scenarios['api-update'] = ['id', 'comment', 'rating'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment', 'rating', 'order_id'], 'required'],
            [['id', 'mech_id', 'order_id'], 'integer'],
            [['rating'], 'integer', 'min' => 1, 'max' => 10],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mech_id' => 'СТО/Магазин',
            'author_id' => 'Автор',
            'order_id' => 'Заказ',
            'comment' => 'Комментарий',
            'rating' => 'Рейтинг',
            'created_at' => 'Добавлен',
            'updated_at' => 'Обновлен',
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
