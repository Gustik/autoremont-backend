<?php

namespace app\models;

use dosamigos\taggable\Taggable;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $birth_date
 * @property string $avatar
 * @property integer $user_id
 * @property string $gcm_id
 * @property string $apns_id
 * @property string $car_brand
 * @property string $car_model
 * @property string $car_color
 * @property integer $car_year
 * @property integer $is_active
 * @property integer $city_id
 * @property string $company_name
 * @property string $company_address
 * @property string $company_logo
 * @property string $lat
 * @property string $lng
 *
 * @property User $user
 */
class Profile extends Model
{
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
        $scenarios['admin-create'] = ['name', 'gcm_id', 'apns_id', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id'];
        $scenarios['admin-update'] = ['name', 'gcm_id', 'apns_id', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id'];
        $scenarios['api-update'] = ['name', 'gcm_id', 'apns_id', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id', 'tagNames'];
        $scenarios['api-view'] = ['name', 'avatar', 'company_name', 'company_address', 'company_logo', 'lat', 'lng', 'birth_date', 'car_brand', 'car_model', 'car_color', 'car_year', 'city_id', 'tagNames'];
        $scenarios['api-view-lite'] = ['name'];

        return $scenarios;
    }

    public function getAttributes($names = null, $except = [])
    {
        $values = parent::getAttributes($names, $except);
        $values['tagNames'] = $this->tagNames;

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
            'company_name' => 'Имя компании',
            'company_address' => 'Адрес компании',
            'company_logo' => 'Логотип компании',
            'avatar' => 'Аватар',
            'lat' => 'Широта',
            'lng' => 'Долгота',
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
}
