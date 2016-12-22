<?php

namespace app\modules\partner\controllers;

use app\models\City;
use Yii;
use app\modules\partner\models\LoginForm;
use app\models\Stat;
use app\models\User;
use app\models\Order;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use app\modules\partner\models\PushForm;

class MainController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'][] = [
            'actions' => ['login', 'error'],
            'allow' => true,
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex($from = null, $to = null, $datasets = null)
    {
        $city = City::findOne($this->user->profile->city_id);

        return $this->render('index', [
            'orderCount' => count($city->orders),
            'userCount' => count($city->profiles),
        ]);
    }

    public function actionPush()
    {
        $model = new PushForm();
        if ($model->load(Yii::$app->request->post()) && $model->push()) {
            Yii::$app->session->setFlash('pushFormSubmitted');
            $model->message = null;

            return $this->render('push', [
                'model' => $model,
            ]);
        }

        return $this->render('push', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect('main/index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
