<?php

namespace app\modules\partner\controllers;

use Yii;
use app\modules\partner\models\MechOrderStatSearch;

/**
 * MechOrderStatController.
 */
class MechOrderStatController extends Controller
{
    /**
     * Lists all MechOrderStat models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MechOrderStatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
