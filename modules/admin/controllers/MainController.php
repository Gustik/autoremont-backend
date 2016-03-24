<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\LoginForm;
use app\models\Stat;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

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
        return $this->render('index', [
            'graphs' => $graphs,
            'from' => $from,
            'to' => $to,
            'datasets' => $datasets
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
