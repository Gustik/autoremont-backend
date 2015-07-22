<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\LoginForm;
use app\modules\admin\models\Stat;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class MainController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'][] = [
            'actions' => ['login', 'error'],
            'allow' => true
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $stat = Stat::getRegistration();
        return $this->render('index', [
            'stat' => $stat
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
