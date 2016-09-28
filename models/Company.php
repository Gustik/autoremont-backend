<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $category
 * @property string $name
 * @property string $phone
 * @property string $description
 * @property integer $is_active
 */
class Company extends Model
{
    const CAT_LAWYER = 1;
    const CAT_EVACUATOR = 2;
    const CAT_COMMISSAR = 3;
    const CAT_SALE = 4;

    public static $categories = [
        self::CAT_LAWYER => 'Автоюрист',
        self::CAT_EVACUATOR => 'Эвакуатор',
        self::CAT_COMMISSAR => 'Аварийный комиссар',
        self::CAT_SALE => 'Акции',
    ];

    public function getCategoryName()
    {
        return static::$categories[$this->category];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-view'] = ['id', 'name', 'phone', 'description'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'description'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['category', 'is_active'], 'integer'],
            [['description'], 'string'],
            [['name', 'phone'], 'string', 'max' => 255],
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
            'category' => 'Category',
            'name' => 'Name',
            'phone' => 'Phone',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ];
    }
}
