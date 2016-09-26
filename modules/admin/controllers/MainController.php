<?php

namespace app\modules\admin\controllers;

use app\helpers\Sms;
use Yii;
use app\modules\admin\models\LoginForm;
use app\models\Stat;
use app\models\User;
use app\models\Order;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use app\modules\admin\models\PushForm;

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

    public function actionIndex($from = null, $to = null, $datasets = null)
    {
        $datasets = ($datasets ? explode(',', $datasets) : ['user_new', 'user_active', 'order_new']);
        $to = ($to ?: date('Y-m-d'));
        $from = ($from ?: date ('Y-m-d', strtotime('-1 month' . $to)));
        $regex = '/^\d{4}-\d{2}-\d{2}$/';
        if (!is_array($datasets) ||
            !preg_match($regex, $to) ||
            !preg_match($regex, $from)) {
            throw new BadRequestHttpException('Bad Request');
        }

        $graphs = Stat::getGraphs($from, $to, $datasets);
        $graphsTotal = Stat::getGraphs($from, $to, ['user_total']);

        return $this->render('index', [
            'graphs' => $graphs,
            'graphsTotal' => $graphsTotal,
            'from' => $from,
            'to' => $to,
            'orderCount' => Order::find()->count(),
            'userCount' => User::find()->count(),
            'datasets' => $datasets,
            'smsBalance' => Sms::balance()
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
