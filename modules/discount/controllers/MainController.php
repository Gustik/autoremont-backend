<?php

namespace app\modules\discount\controllers;

use app\models\DiscountCompany;
use app\models\DiscountUse;
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
        $du = DiscountUse::find()->where(['discount_company_id' => $this->user->id])->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('index', [
            'du' => $du
        ]);
    }

    public function actionConsole()
    {
        $model = new DiscountForm();
        //echo "<pre>"; var_dump(Yii::$app->request->post()); die();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['code-success', 'code' => $model->code]);
        } else {

            return $this->render('console', [
                'model' => $model
            ]);
        }
    }

    public function actionCodeSuccess($code)
    {
        return $this->render('code_success', [
            'user' => DiscountForm::findByCode($code)
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('console');
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
