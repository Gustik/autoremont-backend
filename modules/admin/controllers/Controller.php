<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller as WebController;

class Controller extends WebController
{
    public $user;

    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = '/admin/main/login';
        Yii::$app->errorHandler->errorAction = '/admin/main/error';
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
                        return !Yii::$app->user->isGuest && $this->user->is_admin;
                    },
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        if ($this->user) {
            $this->user->visited_at = date('Y-m-d H:i:s');
            $this->user->save();
        }

        return $result;
    }
}
