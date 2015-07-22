<?php
namespace app\modules\api\controllers\v1;

use Yii;

use app\helpers\ResponseContainer;
use yii\filters\VerbFilter;

use app\models\Profile;

class ProfileController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'update' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionUpdate()
    {
        $profile = $this->user->profile;
        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $this->user->id;
            if (!$profile->save()) {
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $profile->errors);
            }
        }
        $profile->setScenario('api-update');
        if ($profile->load(Yii::$app->request->getBodyParams()) && $profile->save()) {
            return new ResponseContainer(200, 'OK', $profile->safeAttributes);
        }
        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $profile->errors);
    }

    public function actionView()
    {
        $profile = $this->user->getProfile()->one();
        if (!$profile) {
            $profile = new Profile();
            if (!$profile->save()) {
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $profile->errors);
            }
        }
        $profile->setScenario('api-view');
        return new ResponseContainer(200, 'OK', $profile->safeAttributes);
    }
}