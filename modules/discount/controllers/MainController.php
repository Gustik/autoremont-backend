<?php

namespace app\modules\discount\controllers;

use app\modules\discount\models\DiscountForm;
use app\modules\discount\models\LoginForm;
use Yii;

class MainController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'][] = [
            'actions' => ['login', 'error'],
            'allow' => true,
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

    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    public function actionConsole()
    {
        $model = new DiscountForm();
        //echo "<pre>"; var_dump(Yii::$app->request->post()); die();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['code-success']);
        } else {

            return $this->render('console', [
                'model' => $model
            ]);
        }
    }

    public function actionCodeSuccess()
    {
        return $this->render('code_success', [
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect('index');
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
        return $this->redirect('login');
    }


}
