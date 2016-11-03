<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\MechOrderStatSearch;

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
