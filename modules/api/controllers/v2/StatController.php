<?php
namespace app\modules\api\controllers\v2;

use Yii;

use app\helpers\ResponseContainer;

use app\models\StatCall;

class StatController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    public function actionCall($to)
    {
        $model = new StatCall([
            'from' => $this->user->login,
            'to' => $to,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if ($model->save()) {
            return new ResponseContainer(200, 'OK');
        }
        return new ResponseContainer(500, 'Internal Server Error');
    }
}