<?php

namespace app\modules\api\controllers\v3;

use app\helpers\ResponseContainer;
use app\models\City;

class CityController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['index'];

        return $behaviors;
    }

    /**
     * @api {get} api/v3/city/index Список городов
     * @apiName actionIndex
     * @apiGroup City
     * @apiDescription Возвращает список поддерживаемых городов
     *
     * @apiSuccess {Object[]} cities Список городов (City)
     * @apiSuccess {String} cities.id ID города
     * @apiSuccess {String} cities.name Название города
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": [city1, city2]
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionIndex()
    {
        $cityModels = City::findAll(['is_active' => true]);
        $cities = [];
        foreach ($cityModels as $city) {
            $city->setScenario('api-view');
            $cities[] = $city->safeAttributes;
        }

        return new ResponseContainer(200, 'OK', $cities);
    }
}
