<?php

namespace app\modules\api\controllers\v3;

use app\helpers\ResponseContainer;
use app\models\StatCall;

class StatController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    /**
     * @api {get} api/v3/stat/call?to=:to&cat=:cat Запись звонков в компании
     * @apiName actionCall
     * @apiGroup Stat
     * @apiDescription Запись звонков в компании
     *
     * @apiParam {String} to номер телефона вызываемого абонента
     * @apiParam {Number} cat категория компании
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionCall($to, $cat)
    {
        $model = new StatCall([
            'from' => $this->user->login,
            'to' => $to,
            'cat' => $cat,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($model->save()) {
            return new ResponseContainer(200, 'OK');
        }

        return new ResponseContainer(500, 'Internal Server Error');
    }
}
