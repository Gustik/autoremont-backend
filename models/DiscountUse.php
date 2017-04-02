<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discount_use".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $discount_company_id
 * @property string $params
 * @property string $created_at
 *
 * @property DiscountCompany $discountCompany
 * @property User $user
 */
class DiscountUse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount_use';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'discount_company_id', 'created_at'], 'required'],
            [['user_id', 'discount_company_id'], 'integer'],
            [['params'], 'string'],
            [['created_at'], 'safe'],
            [['discount_company_id'], 'exist', 'skipOnError' => true, 'targetClass' => DiscountCompany::className(), 'targetAttribute' => ['discount_company_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'discount_company_id' => 'Discount Company ID',
            'params' => 'Params',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountCompany()
    {
        return $this->hasOne(DiscountCompany::className(), ['id' => 'discount_company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
