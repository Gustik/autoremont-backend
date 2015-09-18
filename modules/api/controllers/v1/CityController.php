<?php
namespace app\modules\api\controllers\v1;

use Yii;

use app\helpers\ResponseContainer;

use app\models\City;

class CityController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['index'];
        return $behaviors;
    }

    public function actionIndex()
    {
        $cityModels = City::findAll(['is_active' => true]);
        $cities = [];
        foreach ($cityModels as $city) {
            $city->setScenario('api-view');
            $cities[] = $city->safeAttributes;
        }
        return new ResponseContainer(200, 'OK', $cities);
    }
}