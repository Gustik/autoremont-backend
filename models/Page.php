<?php

namespace app\models;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $address
 * @property string $title
 * @property string $text
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'title', 'text'], 'required'],
            [['title', 'text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Путь',
            'title' => 'Заголовок',
            'text' => 'Текст',
        ];
    }
}
