<?php

namespace app\modules\api\controllers\v2;

use Yii;
use yii\web\Response;
use yii\web\Controller as BaseController;
use yii\filters\ContentNegotiator;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\AccessControl;
use yii\web\HttpException;

class Controller extends BaseController
{
    public $user;

    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'application/xml' => Response::FORMAT_XML,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                        if (isset($this->user)) {
                            return $this->user->is_active;
                        }

                        return true;
                    },
                ],
            ],
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        $result = parent::beforeAction($action);
        $this->user = Yii::$app->user->identity;
        if ($this->user && $this->user->banned_to && $this->user->banned_to > date('Y-m-d H:i:s')) {
            throw new HttpException(423, 'Бан до '.$this->user->banned_to);
        }

        return $result;
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
