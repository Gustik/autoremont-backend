<?php
namespace app\modules\api\controllers\v2;

use Yii;

use app\helpers\ResponseContainer;

use app\models\Company;

class CompanyController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    public function actionIndex($catID)
    {
        $models = Company::findAll(['is_active' => true, 'category' => $catID]);
        $companies = [];
        foreach ($models as $company) {
            $company->setScenario('api-view');
            $companies[] = $company->safeAttributes;
        }
        return new ResponseContainer(200, 'OK', $companies);
    }
}