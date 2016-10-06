<?php

namespace app\modules\api\controllers\v3;

use app\helpers\ResponseContainer;
use Yii;
use app\models\OrderTag;
use yii\filters\VerbFilter;

/**
 * OrderTagController implements the CRUD actions for OrderTag model.
 */
class OrderTagController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
        ];
        return $behaviors;
    }

    public function actionIndex($query = '')
    {
        /** @var $models OrderTag[] */
        $models = OrderTag::find()->where(['like', 'name', $query])->all();
        $items = [];

        foreach ($models as $model) {
            $items[] = ['name' => $model->name];
        }

        return new ResponseContainer(200, 'OK', $items);
    }

}
