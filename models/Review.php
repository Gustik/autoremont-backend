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
 * @property User $author
 * @property User $mech
 */
class Review extends Model
{
    public $is_active = 1;

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

    public function getAttributes($names = null, $except = [])
    {
        $values = parent::getAttributes($names, $except);
        $values['authorName'] = $this->getAuthorName();

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-create'] = ['order_id', 'comment', 'mech_id', 'rating'];
        $scenarios['api-update'] = ['id', 'comment', 'rating'];
        $scenarios['api-view'] = ['id', 'order_id', 'mech_id', 'author_id', 'comment', 'rating', 'authorName'];

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
            [['rating'], 'number', 'min' => 0.5, 'max' => 5],
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

    public function getAuthorName()
    {
        return $this->author->profile->name;
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
