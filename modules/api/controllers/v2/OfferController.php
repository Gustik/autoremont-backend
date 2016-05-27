<?php
namespace app\modules\api\controllers\v2;

use Yii;

use app\helpers\ResponseContainer;
use app\helpers\PushHelper;
use app\models\Offer;
use app\models\Order;

class OfferController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    public function actionProduce()
    {
        $id = Yii::$app->request->getBodyParam('order_id');
        if ($order = Order::findOne(['id' => $id]) && $order->is_active) {
            $offer = Offer::findProduce($id, $this->user->id);
            if ($offer->load(Yii::$app->request->getBodyParams()) && $offer->save()) {
                PushHelper::send($offer->author->profile->gcm_id, "Новое предложение по вашему заказу!");
                $offer->setScenario('api-view');
                return new ResponseContainer(200, 'OK', $offer->safeAttributes);
            }
            return new ResponseContainer(500, 'Внутренняя ошибка сервера', $offer->errors);
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionView($id)
    {
        if ($offer = Offer::findOne(['order_id' => $id, 'author_id' => $this->user->id]) && $offer->is_active) {
            $offer->setScenario('api-view');
            return new ResponseContainer(200, 'OK', $offer->safeAttributes);
        }
        return new ResponseContainer(404, 'Предложение не найдено');
    }
}