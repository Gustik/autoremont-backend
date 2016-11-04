<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\StatCall;
use app\modules\admin\models\StatCallSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StatCallController implements the CRUD actions for StatCall model.
 */
class StatCallController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all StatCall models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatCallSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the StatCall model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return StatCall the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StatCall::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
