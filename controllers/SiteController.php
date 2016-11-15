<?php

namespace app\controllers;

use app\models\Page;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return parent::behaviors();
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
        $this->layout = 'new-style';

        return $this->render('index');
    }

    public function actionLicense()
    {
        $page = Page::findOne(1);

        return $this->render('license', ['page' => $page]);
    }
}
