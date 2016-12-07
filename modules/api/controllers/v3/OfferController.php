<?php

namespace app\modules\api\controllers\v3;

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

    /**
     * @apiName actionProduce
     * @apiGroup Offer
     * @apiDescription Создание/обновление предложения
     *
     * @api {post} api/v3/offer/produce Создание/обновление предложения
     *
     * @apiParam {Object} Offer Предложение
     * @apiParam {Number} Offer.order_id ID заказа
     * @apiParam {String} Offer.text Текст предложения
     *
     * @apiSuccess {Object} Offer Преложение
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": Offer
     *     }
     *
     * @apiErrorExample {json} Ошибки:
     *     {
     *       "status": 404,
     *       "message": "Заявка не найдена"
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionProduce()
    {
        $id = Yii::$app->request->getBodyParam('order_id');
        $order = Order::findOne(['id' => $id]);
        if ($order && $order->is_active) {

            if (!$this->user->can_work // Если не может работать (не оплачен аккаунт)
                && $order->category_id == 1 // для магазинов пока бесплатно (category_id 1 - ремонт, 2 - запчасти)
                && $this->user->profile->city->need_payment // Если в городе включена тарификация
            ) {
                return new ResponseContainer(403, 'Необходим платеж');
            }

            $offer = Offer::findProduce($id, $this->user->id);
            if ($offer->load(Yii::$app->request->getBodyParams())) {
                $offer->is_call = false;
                if ($offer->save()) {
                    PushHelper::send(
                        $offer->order->author->profile->gcm_id,
                        'Новое предложение по вашему заказу!',
                        ['type' => PushHelper::TYPE_OFFER, 'order_id' => $offer->order->id, 'cat' => $offer->order->category_id]
                    );
                    $offer->setScenario('api-view');
                    unset($offer->author);

                    return new ResponseContainer(200, 'OK', $offer->safeAttributes);
                }
            }

            return new ResponseContainer(500, 'Внутренняя ошибка сервера', $offer->errors);
        }

        return new ResponseContainer(404, 'Заявка не найдена');
    }

    /**
     * @apiName actionView
     * @apiGroup Offer
     * @apiDescription Просмотр предложения
     *
     * @api {get} api/v3/offer/view?id=:id Просмотр предложения
     *
     * @apiParam {Number} id ID Заказа
     *
     * @apiSuccess {Object} Offer Преложение
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": Offer
     *     }
     *
     * @apiErrorExample {json} Ошибки:
     *     {
     *       "status": 404,
     *       "message": "Предложение не найдена"
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionView($id)
    {
        $offer = Offer::findOne(['order_id' => $id, 'author_id' => $this->user->id]);
        if ($offer && $offer->is_active) {
            $offer->setScenario('api-view');

            return new ResponseContainer(200, 'OK', $offer->safeAttributes);
        }

        return new ResponseContainer(404, 'Предложение не найдено');
    }
}
