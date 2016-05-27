<?php
namespace app\modules\api\controllers\v2;

use Yii;
use app\helpers\Phone;
use app\helpers\ResponseContainer;
use app\helpers\Sms;
use app\models\User;
use app\models\Profile;
use app\models\Variable;

class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['get-code', 'verify-code'];
        return $behaviors;
    }

    /**
     * @param  string
     * @return ResponseContainer
     */
    public function actionGetCode($phone)
    {
        $login = Phone::prepare($phone);
        if ($login) {
            $user = User::findIdentityByLogin($login);
            $code = ( Variable::getParam('environment') == 'DEV' ? 1111 : mt_rand(1000, 9999) );
            if ($user) {
                if (!$user->sms_code) {
                    $user->sms_code = $code;
                    $user->sms_code_time = 15*60;
                }
            } else {
                $user = new User();
                $user->login = $login;
                $user->sms_code = $code;
                $user->sms_code_time = 15*60;
            }
            if ($user->save()) {
                $message = 'Код подтверждения: ' . $user->sms_code;
                if (Sms::send($user->login, $message)) {
                    return new ResponseContainer(200, 'Вам отправлено СМС с кодом подтверждения');
                }
                return new ResponseContainer(500, 'Ошибка отправки СМС');
            }
            return new ResponseContainer(500, 'Внутренняя ошибка сервера', $user->errors);
        }
        return new ResponseContainer(400, 'Неверный формат номера телефона');
    }

    public function actionVerifyCode($phone, $code)
    {
        $login = Phone::prepare($phone);
        if ($login) {
            $user = User::findIdentityByLogin($login);
            if ($user) {
                if ($user->validateSmsCode($code)) {
                    $user->access_token = Yii::$app->getSecurity()->generatePasswordHash($user->sms_code);
                    $user->sms_code = null;
                    if ($user->save()) {
                        $profile = new Profile();
                        $profile->user_id = $user->id;
                        $profile->save();
                        return new ResponseContainer(200, 'OK', ['token' => $user->access_token]);
                    }
                    return new ResponseContainer(500, 'Внутренняя ошибка сервера', $user->errors);
                }
                return new ResponseContainer(400, 'Неверный код');
            }
            return new ResponseContainer(404, 'Пользователь не найден');
        }
    }

    public function actionCheckToken()
    {
        return new ResponseContainer();
    }

    public function actionResetToken()
    {
        $this->user->access_token = Yii::$app->getSecurity()->generatePasswordHash(mt_rand());
        if ($this->user->save()) {
            return new ResponseContainer(200, 'OK', ['token' => $this->user->access_token]);
        }
        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $user->errors);
    }
}