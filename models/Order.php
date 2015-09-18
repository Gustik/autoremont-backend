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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['new_calls']);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['api-create'] = ['description', 'price', 'car_brand', 'car_model', 'car_year', 'car_color'];
        $scenarios['api-update'] = ['description', 'car_brand', 'car_model', 'car_year', 'car_color'];
        $scenarios['api-view'] = ['id', 'description', 'price', 'created_at', 'updated_at', 'car_brand', 'car_model', 'car_year', 'car_color', 'author_id', 'new_calls', 'calls', 'executor', 'author'];
        $scenarios['api-view-without-calls'] = ['id', 'description', 'price', 'created_at', 'updated_at', 'car_brand', 'car_model', 'car_year', 'car_color', 'author_id', 'new_calls', 'executor', 'author'];
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
            [['price', 'author_id', 'is_active'], 'integer'],
            [['car_brand', 'car_model', 'car_year', 'car_color'], 'string', 'max' => 255]
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
            'description' => 'Description',
            'price' => 'Price',
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
    public function getCalls()
    {
        return $this->hasMany(Call::className(), ['order_id' => 'id']);
    }

    public static function findFree()
    {
        return static::find()->where(['executor_id' => null, 'is_active' => true])->andWhere(['!=', 'author_id', Yii::$app->user->identity->id])->andWhere(['>', 'updated_at', date("Y-m-d H:i:s", time() - 60*60*24*2)]);
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
            return true;
        }
        return false;
    }
}