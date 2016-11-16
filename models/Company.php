<?php

namespace app\models;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $category
 * @property string $name
 * @property string $phone
 * @property string $url
 * @property string $description
 * @property int $is_active
 */
class Company extends Model
{
    const CAT_LAWYER = 1; // Автоюристы
    const CAT_EVACUATOR = 2; // Эваукуаторы
    const CAT_COMMISSAR = 3; //Автокомиссары
    const CAT_SALE = 4; // Акции
    const CAT_OUTREACH_SERVICE = 5; // Выездные услуги
    const CAT_INSURANCE = 6; // Страховые компании

    public static $categories = [
        self::CAT_LAWYER => 'Автоюрист',
        self::CAT_EVACUATOR => 'Эвакуатор',
        self::CAT_COMMISSAR => 'Аварийный комиссар',
        self::CAT_SALE => 'Акции',
        self::CAT_OUTREACH_SERVICE => 'Выездные услуги',
        self::CAT_INSURANCE => 'Страховые компании',
    ];

    public function getCategoryName()
    {
        return static::$categories[$this->category];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-view'] = ['id', 'name', 'phone', 'url', 'description'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'description'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['category', 'is_active'], 'integer'],
            [['description'], 'string'],
            [['name', 'phone', 'url'], 'string', 'max' => 255],
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
            'category' => 'Category',
            'name' => 'Name',
            'phone' => 'Phone',
            'url' => 'Url',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ];
    }
}
