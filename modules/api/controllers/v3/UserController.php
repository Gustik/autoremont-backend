<?php

namespace app\modules\api\controllers\v3;

use Yii;
use app\helpers\Phone;
use app\helpers\ResponseContainer;
use app\helpers\Sms;
use app\models\User;
use app\models\Profile;
use app\models\Variable;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['get-code', 'verify-code'];

        return $behaviors;
    }

    /**
     * @api {get} api/v3/user/get-code?phone=:phone Получить код подтверждения
     * @apiName actionGetCode
     * @apiGroup User
     * @apiDescription Получить код подтверждения через СМС (не требует авторизации)
     * @apiParam {String} phone номер телефона на который будет отправлено СМС. Формат: \^+\d\d{10}$\.
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200
     *       "message": "Вам отправлено СМС с кодом подтверждения"
     *     }
     * @apiErrorExample {json} Ошибки:
     *     {
     *       "status": 400,
     *       "message": "Неверный формат номера телефона"
     *     }
     *     {
     *       "status": 500,
     *       "message": "Ошибка отправки СМС"
     *     }
     * @apiVersion 3.0.0
     *
     * @param $phone
     *
     * @return ResponseContainer
     */
    public function actionGetCode($phone)
    {
        $login = Phone::prepare($phone);
        if ($login) {
            $user = User::findIdentityByLogin($login);
            $code = (Variable::getParam('environment') == 'DEV' ? 1111 : mt_rand(1000, 9999));

            // Тестовый аккаунт для AppStore
            if ($login == User::TEST_LOGIN) {
                $code = User::TEST_CODE;
            }

            if ($user) {
                if (!$user->sms_code) {
                    $user->sms_code = $code;
                    $user->sms_code_time = 15 * 60;
                }
            } else {
                $user = new User();
                $user->login = $login;
                $user->sms_code = $code;
                $user->sms_code_time = 15 * 60;
            }
            if ($user->save()) {
                $message = 'Код подтверждения: '.$user->sms_code;
                if (Sms::send($user->login, $message)) {
                    return new ResponseContainer(200, 'Вам отправлено СМС с кодом подтверждения');
                }

                return new ResponseContainer(500, 'Ошибка отправки СМС');
            }

            return new ResponseContainer(500, 'Внутренняя ошибка сервера', $user->errors);
        }

        return new ResponseContainer(400, 'Неверный формат номера телефона');
    }

    /**
     * @api {get} api/v3/user/verify-code?phone=:phone&code=:code Подтверждение номера
     * @apiName actionVerifyCode
     * @apiGroup User
     * @apiDescription Подтверждение номера (не требует авторизации)
     * @apiParam {String} phone номер телефона на который пришел СМС. Формат: \^+7\d{10}$\.
     * @apiParam {String} code код подтверждения из СМС..
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": {"token": "<токен доступа>"}
     *     }
     * @apiErrorExample {json} Ошибки:
     *     {
     *       "status": 400,
     *       "message": "Неверный код"
     *     }
     *     {
     *       "status": 404,
     *       "message": "Пользователь не найден (возвращается в случае указания номера телефона, отличного от номера во время вызова get-code)"
     *     }
     * @apiVersion 3.0.0
     *
     * @param $phone
     * @param $code
     *
     * @return ResponseContainer
     *
     * @throws \yii\base\Exception
     */
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

    /**
     * @api {get} api/v3/user/check-token Проверка токена
     * @apiName actionCheckToken
     * @apiGroup User
     * @apiDescription Проверка токена на валидность (не сохраняет данные, нет обязательных параметров)
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK"
     *     }
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionCheckToken()
    {
        return new ResponseContainer();
    }

    /**
     * @api {get} api/v3/user/reset-token Пересоздание токена
     * @apiName actionCheckToken
     * @apiGroup User
     * @apiDescription Пересоздание токена (нет обязательных параметров).
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": {"token": "<токен доступа>"}
     *     }
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     *
     * @internal param $phone
     */
    public function actionResetToken()
    {
        $this->user->access_token = Yii::$app->getSecurity()->generatePasswordHash(mt_rand());
        if ($this->user->save()) {
            return new ResponseContainer(200, 'OK', ['token' => $this->user->access_token]);
        }

        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $this->user->errors);
    }

    /**
     * @api {get} api/v3/user/view?phone=:phone Просмотр пользователя
     * @apiName actionView
     * @apiGroup User
     * @apiDescription Просмотр профиля пользователя.
     * @apiParam {String} phone номер телефона пользователя.
     *
     * @apiSuccess {Object} User Объект пользователя
     * @apiSuccess {Profile} User.profile Объект профиля пользователя
     * @apiSuccess {String} User.profile.name Дата создания преложения
     * @apiSuccess {Number} User.rating Рейтинг пользователя
     * @apiSuccess {String} User.login Телефон(логин) пользователя
     * @apiSuccess {Object[]} User.reviews Отзывы
     * @apiSuccess {Number} User.reviews.id ID отзыва
     * @apiSuccess {String} User.reviews.authorName Имя автора отзыва
     * @apiSuccess {Number} User.reviews.order_id ID заказа, на который оставлен отзыв
     * @apiSuccess {Number} User.reviews.mech_id ID мастера, которму оставлен отзыв
     * @apiSuccess {Number} User.reviews.rating Оценка к отзыву 1..10
     * @apiSuccess {String} User.reviews.comment Текст отзыва
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": User
     *     }
     * @apiVersion 3.0.0
     *
     * @param $phone
     *
     * @return ResponseContainer
     *
     * @throws \yii\base\Exception
     */
    public function actionView($phone)
    {
        $login = Phone::prepare($phone);
        if (!$login) {
            return new ResponseContainer(400, 'Некорретный номер телефона');
        }

        $user = User::findIdentityByLogin($login);
        if (!$user) {
            return new ResponseContainer(404, 'Пользователь не найден');
        }

        $user->setScenario('api-view');
        $user->profile->setScenario('api-view');
        foreach ($user->reviews as $review) {
            $review->setScenario('api-view');
        }

        return new ResponseContainer(200, 'OK', $user->safeAttributes);
    }
}
