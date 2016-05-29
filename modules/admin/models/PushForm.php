<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;

use app\models\User;
use app\helpers\Phone;
use app\helpers\PushHelper;

class PushForm extends Model
{
    public $username;
    public $regID;
    public $message;

    public function rules()
    {
        return [
            [['username', 'message'], 'required'],
            [['regID'], 'required', 'message' => 'У данного пользователя отсутствует регистрационный токен']
];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Номер телефона',
            'message' => 'Сообщение',
            'regID' => 'Регистрационный токен'
        ];
    }

    public function push()
    {
        if ($user = User::findOne(['login' => $this->username])) {
            $this->regID = $user->profile->gcm_id;
            if ($this->validate()) {
                if (PushHelper::send($this->regID, $this->message)) {
                    return true;
                } else {
                    Yii::$app->session->setFlash('pushSendFail');
                }
                return false;
            }
        } else {
            $this->addError('username', "Пользователь $this->username не найден");
        }
        return false;
    }
}
