<?php

namespace app\models;

use dosamigos\taggable\Taggable;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\helpers\Json;
use yii\imagine\Image;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $phone
 * @property string $birth_date
 * @property string $avatar
 * @property int $user_id
 * @property string $gcm_id
 * @property string $apns_id
 * @property string $car_brand
 * @property string $car_model
 * @property string $car_color
 * @property int $car_year
 * @property int $is_active
 * @property int $city_id
 * @property string $company_name
 * @property string $company_address
 * @property string $company_logo
 * @property string $lat
 * @property string $lng
 * @property User $user
 */
class Profile extends Model
{
    public $company_logo_image;
    public $crop_info;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['admin-create'] = ['name', 'gcm_id', 'apns_id', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id', 'company_name', 'company_address', 'company_logo_image'];
        $scenarios['admin-update'] = ['name', 'gcm_id', 'apns_id', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id', 'company_name', 'company_address', 'company_logo_image'];
        $scenarios['api-update'] = ['name', 'gcm_id', 'apns_id', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id', 'tagNames'];
        $scenarios['api-view'] = ['name', 'avatar', 'phone', 'company_name', 'company_address', 'company_logo', 'lat', 'lng',
            'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id', 'tagNames'];
        $scenarios['api-view-lite'] = ['name'];

        return $scenarios;
    }

    public function getAttributes($names = null, $except = [])
    {
        $values = parent::getAttributes($names, $except);
        $values['tagNames'] = $this->tagNames;
        $values['phone'] = $this->phone;

        return $values;
    }

    public function behaviors()
    {
        return [
            [
                'class' => Taggable::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['created_at', 'updated_at', 'birth_date'], 'safe'],
            [['birth_date'], 'date', 'format' => 'php:Y-m-d'],
            [['user_id', 'is_active', 'car_year'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['name', 'avatar', 'gcm_id', 'apns_id', 'car_brand', 'car_model', 'car_color', 'company_name', 'company_address', 'company_logo'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [
                'company_logo_image',
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
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'name' => 'Имя',
            'birth_date' => 'Дата рождения',
            'user_id' => 'ID пользователя',
            'gcm_id' => 'Регистрационный Android ID',
            'apns_id' => 'Регистрационный Apple ID',
            'car_brand' => 'Марка автомобиля',
            'car_model' => 'Модель автомобиля',
            'car_color' => 'Цвет автомобиля',
            'car_year' => 'Год выпуска автомобиля',
            'is_active' => 'Активен',
            'company_name' => 'Имя СТО/Магазина',
            'company_address' => 'Адрес СТО/Магазина',
            'company_logo' => 'Логотип СТО/Магазина',
            'company_logo_image' => 'Логотип СТО/Магазина',
            'avatar' => 'Аватар',
            'lat' => 'Широта',
            'lng' => 'Долгота',
            'city_id' => 'Город',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(OrderTag::className(), ['id' => 'order_tag_id'])->viaTable('profile_tag_assign', ['profile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhone()
    {
        return $this->user->login;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->name = ($this->name ?: 'Аноним');
            $this->birth_date = ($this->birth_date ?: '1970-01-01');

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->company_logo_image) {
            // open image
            $image = Image::getImagine()->open($this->company_logo_image->tempName);

            // rendering information about crop of ONE option
            $cropInfo = Json::decode($this->crop_info)[0];
            $cropInfo['dWidth'] = (int) $cropInfo['dWidth']; //new width image
            $cropInfo['dHeight'] = (int) $cropInfo['dHeight']; //new height image
            $cropInfo['x'] = $cropInfo['x']; //begin position of frame crop by X
            $cropInfo['y'] = $cropInfo['y']; //begin position of frame crop by Y

            //saving thumbnail
            $newSizeThumb = new Box($cropInfo['dWidth'], $cropInfo['dHeight']);
            $cropSizeThumb = new Box(800, 250); //frame size of crop
            $cropPointThumb = new Point($cropInfo['x'], $cropInfo['y']);
            $pathThumbImage = Yii::getAlias('@webroot/img/upload/company-logo/').$this->company_logo;

            $image->resize($newSizeThumb)
                ->crop($cropPointThumb, $cropSizeThumb)
                ->save($pathThumbImage, ['quality' => 100]);
        }
    }
}
