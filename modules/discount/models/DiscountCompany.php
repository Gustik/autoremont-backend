<?php
namespace app\modules\discount\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class DiscountCompany extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'discount_company';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return false;
    }


    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
}