<?php

namespace app\controllers;

use app\models\Page;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

    public function actionPage($id)
    {
        $page = Page::findOne($id);
        if(!$page) {
            throw new NotFoundHttpException();
        }
        return $this->render('page', ['page' => $page]);
    }
}
