<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discount_company".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $discount
 * @property string $description
 * @property string $password
 *
 * @property Company $company
 * @property DiscountUse[] $discountUses
 */
class DiscountCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id'], 'required'],
            [['company_id'], 'integer'],
            [['description'], 'string'],
            [['discount'], 'string', 'max' => 5],
            [['password'], 'string', 'max' => 255],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'discount' => 'Discount',
            'description' => 'Description',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountUses()
    {
        return $this->hasMany(DiscountUse::className(), ['discount_company_id' => 'id']);
    }
}
