<?php

namespace app\models;

use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;

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
 * @property string $logo
 * @property string $description
 * @property int $city_id
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
    const CAT_AZS = 7; // АЗС

    public static $categories = [
        self::CAT_LAWYER => 'Автоюрист',
        self::CAT_EVACUATOR => 'Эвакуатор',
        self::CAT_COMMISSAR => 'Аварийный комиссар',
        self::CAT_SALE => 'Акции',
        self::CAT_OUTREACH_SERVICE => 'Выездные услуги',
        self::CAT_INSURANCE => 'Страховые компании',
        self::CAT_AZS => 'АЗС',
    ];

    public $logo_image;
    public $crop_info;
    const LOGO_WIDTH = 800;
    const LOGO_HEIGHT = 400;

    public function getCategoryName()
    {
        return static::$categories[$this->category];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
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
        $scenarios['api-view'] = ['id', 'name', 'phone', 'url', 'logo', 'description'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'city_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['category', 'is_active', 'city_id'], 'integer'],
            [['description'], 'string'],
            [['name', 'phone', 'url', 'logo'], 'string', 'max' => 255],
            [
                'logo_image',
                'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],
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
            'city_id' => 'City',
            'is_active' => 'Is Active',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->logo_image) {
            // open image
            $image = Image::getImagine()->open($this->logo_image->tempName);

            // rendering information about crop of ONE option
            $cropInfo = Json::decode($this->crop_info)[0];
            $cropInfo['dWidth'] = (int) $cropInfo['dWidth']; //new width image
            $cropInfo['dHeight'] = (int) $cropInfo['dHeight']; //new height image

            //saving thumbnail
            $newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
            $cropSizeThumb = new Box(self::LOGO_WIDTH, self::LOGO_HEIGHT); //frame size of crop
            $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
            $pathThumbImage = Yii::getAlias('@webroot/img/upload/companies/').$this->logo;

            $image->resize($newSizeThumb)
                ->crop($cropPointThumb, $cropSizeThumb)
                ->save($pathThumbImage, ['quality' => 80]);
        }
    }
}
