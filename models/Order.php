<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 * @property integer $price
 * @property string $car_brand
 * @property string $car_model
 * @property string $car_year
 * @property string $car_color
 * @property integer $author_id
 * @property integer $is_active
 *
 * @property User $author
 * @property User $executor
 * @property Call[] $calls
 */
class Order extends Model
{
    public $new_calls;
    public $new_offers;
    public $my_offer;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['new_calls', 'new_offers', 'my_offer']);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-create'] = ['description', 'price', 'car_brand', 'car_model', 'car_year', 'car_color', 'category_id'];
        $scenarios['api-update'] = ['description', 'car_brand', 'car_model', 'car_year', 'car_color', 'category_id'];
        $scenarios['api-view'] = ['id', 'description', 'price', 'created_at', 'updated_at', 'car_brand', 'car_model', 'car_year', 'car_color', 'author_id', 'author', 'category_id', 'new_calls', 'new_offers', 'my_offer', 'offers', 'calls', 'executor', 'author', 'category'];
        $scenarios['api-view-without-calls'] = ['id', 'description', 'price', 'created_at', 'updated_at', 'car_brand', 'car_model', 'car_year', 'car_color', 'author_id', 'category_id', 'new_calls', 'new_offers', 'executor', 'author', 'category'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'price'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['price', 'author_id', 'is_active', 'category_id'], 'integer'],
            [['car_brand', 'car_model', 'car_year', 'car_color'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 512],
            ['category_id', 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'description' => 'Описание',
            'price' => 'Цена',
            'car_brand' => 'Car Brand',
            'car_model' => 'Car Model',
            'car_year' => 'Car Year',
            'car_color' => 'Car Color',
            'author_id' => 'Author ID',
            'executor_id' => 'Executor ID',
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
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
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
    public function getCalls()
    {
        return $this->hasMany(Call::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['order_id' => 'id']);
    }

    /**
     * @return integer
     */
    public function getOffersCount()
    {
        return $this->hasMany(Offer::className(), ['order_id' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getNewOffers()
    {
        return Offer::find()->where(['order_id' => $this->id, 'is_new' => true])->count();
    }

    public function getMyOffer()
    {
        return Offer::find()->where(['order_id' => $this->id, 'author_id' => Yii::$app->user->id])->one();
    }

    public static function findFree($category_id)
    {
        return static::find()
            ->where(['executor_id' => null, 'is_active' => true])
            // don't show self-created orders
            ->andWhere(['!=', 'author_id', Yii::$app->user->identity->id])
            // date of last update must be earlier than 2 days
            ->andWhere(['>', 'updated_at', date("Y-m-d H:i:s", time() - 60*60*24*2)])
            // show only order from master's city
            ->andWhere(['city_id' => Yii::$app->user->identity->profile->city_id])
            // show only order from selected category
            ->andWhere(['category_id' => $category_id])
            ->orderBy(['updated_at' => SORT_ASC]);
    }

    public function readAllOffers()
    {
        foreach ($this->offers as $offer) {
            $offer->is_new = false;
            $offer->save();
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->author_id = Yii::$app->user->identity->id;
            }
            $this->description = trim($this->description);
            return true;
        }
        return false;
    }
}