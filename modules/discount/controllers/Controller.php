<?php

namespace app\modules\discount\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller as WebController;

class Controller extends WebController
{
    public $user;

    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = '/discount/main/error';
        $this->layout = 'main';
    }

    public function behaviors()
    {
        $this->user = Yii::$app->user->identity;
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        return !Yii::$app->user->isGuest;
                    },
                ],
            ],
        ];

        return $behaviors;
    }
}
