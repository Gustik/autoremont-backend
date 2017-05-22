<?php

namespace app\modules\discount\models;

use app\models\DiscountUse;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\db\Expression;

class DiscountForm extends Model
{
    public $code;
    public $params;

    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'string', 'max' => 6, 'min' => 6],
            [['params'], 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->findByCode($this->code)) {
            $this->addError('code', 'Пользователь с таким кодом не найден');

            return false;
        }
        $model = new DiscountUse();
        $model->discount_company_id = Yii::$app->user->id;
        $model->user_id = (int) $this->code;
        $model->params = empty($this->params) ? '{}' : $this->params;
        $model->created_at = new Expression('NOW()');

        return $model->save();
    }

    public static function findByCode($code)
    {
        $id = (int) $code;

        return User::findOne($id);
    }
}
