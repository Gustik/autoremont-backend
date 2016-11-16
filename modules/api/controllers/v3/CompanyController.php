<?php

namespace app\modules\api\controllers\v3;

use app\helpers\ResponseContainer;
use app\models\Company;

class CompanyController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    /**
     * @api {get} api/v3/company/index?category=:category Список компаний
     * @apiName actionIndex
     * @apiGroup Company
     * @apiDescription Возвращает список компаний по категории
     *
     * @apiParam {String} category категория компании (1 - автоюристы, 2 - эвакуаторы, 3 - комиссары, 4 - акции, 5 - выездные услуги)
     *
     * @apiSuccess {Object[]} Company Список компаний
     * @apiSuccess {String} Company.id ID компании
     * @apiSuccess {String} Company.name Название города
     * @apiSuccess {String} Company.phone Телефон
     * @apiSuccess {String} Company.url Url
     * @apiSuccess {String} Company.description Описание компании
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": [Company1, Company2]
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionIndex($category)
    {
        $models = Company::findAll(['is_active' => true, 'category' => $category]);
        $companies = [];
        foreach ($models as $company) {
            $company->setScenario('api-view');
            $companies[] = $company->safeAttributes;
        }

        return new ResponseContainer(200, 'OK', $companies);
    }
}
