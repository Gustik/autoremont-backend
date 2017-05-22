<?php

namespace app\modules\api\controllers\v3;

use Yii;
use app\helpers\ResponseContainer;
use yii\filters\VerbFilter;
use app\models\Profile;

class ProfileController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'update' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @apiName actionUpdate
     * @apiGroup Profile
     * @apiDescription Обновление профиля.
     *
     * @api {post} api/v3/profile/update Обновление профиля
     *
     * @apiParam {Object} Profile Профиль
     * @apiParam {String} [Profile.name] Имя
     * @apiParam {String} [Profile.birth_date] Дата рождения
     * @apiParam {String} [Profile.car_brand] Марка машиный
     * @apiParam {String} [Profile.car_model] Модель машины
     * @apiParam {String} [Profile.car_color] Цвет машины
     * @apiParam {String} [Profile.gcm_id] Android GSM ID
     * @apiParam {String} [Profile.apns_id] Apple APNS ID
     * @apiParam {Number} Profile.city_id Город
     * @apiParam {Number} [Profile.tagNames] Теги заказов, на которые подписан
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": Profile
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionUpdate()
    {
        $profile = $this->user->profile;
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $this->user->id;
            if (!$profile->save()) {
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $profile->errors);
            }
        }
        $profile->setScenario('api-update');
        if ($profile->load(Yii::$app->request->getBodyParams())) {
            if ($profile->city && $profile->save()) {
                return new ResponseContainer(200, 'OK', $profile->safeAttributes);
            }
        }

        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $profile->errors);
    }

    /**
     * @api {get} api/v3/profile/view Просмотр профиля
     * @apiName ActionView
     * @apiGroup Profile
     * @apiDescription Просмотр своего профиля (не сохраняет данные, нет обязательных параметров).
     * @apiSuccess {Object} Profile Объект профиля пользователя
     * @apiSuccess {String} Profile.name Имя пользователя
     * @apiSuccess {String} Profile.user_id ID пользователя
     * @apiSuccess {String} Profile.avatar url аватара
     * @apiSuccess {String} Profile.phone телефон пользователя (user.login)
     * @apiSuccess {String} Profile.bill_account_days количество оставшихся дней подписки
     * @apiSuccess {String} Profile.company_name Имя СТО/Магазина
     * @apiSuccess {String} Profile.company_address Адрес СТО/Магазина
     * @apiSuccess {String} Profile.company_logo url логотипа СТО/Магазина
     * @apiSuccess {String} Profile.lat Широта
     * @apiSuccess {String} Profile.lng И долгота где находится СТО/Магазин
     * @apiSuccess {String} Profile.birth_date
     * @apiSuccess {String} Profile.car_brand
     * @apiSuccess {String} Profile.car_model
     * @apiSuccess {String} Profile.car_color
     * @apiSuccess {String} Profile.car_year
     * @apiSuccess {String} Profile.city_id
     * @apiSuccess {String} Profile.tagNames Теги заказов, на которые подписан
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": Profile
     *     }
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionView()
    {
        $profile = $this->user->getProfile()->one();
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $this->user->id;
            if (!$profile->save()) {
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $profile->errors);
            }
        }
        $profile->setScenario('api-view');

        return new ResponseContainer(200, 'OK', $profile->safeAttributes);
    }
}
