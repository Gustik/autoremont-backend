<?php

namespace app\modules\api\controllers\v3;

use app\helpers\ResponseContainer;
use app\models\Order;
use app\models\Review;
use app\models\User;
use Yii;

class ReviewController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    /**
     * @apiName actionCreate
     * @apiGroup Review
     * @apiDescription Создание отзыва к СТО/Магазину
     *
     * @api {post} api/v3/review/create Создание отзыва
     *
     * @apiParam {Object} Review Отзыв
     * @apiParam {Number} Review.order_id ID заказа
     * @apiParam {String} Review.comment Текст отзыва
     * @apiParam {Number} Review.mech_id ID СТО/Магазина
     * @apiParam {Number} Review.rating Оценка от 1 до 10
     *
     * @apiSuccess {Object} Review Отзыв
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": Review
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
    public function actionCreate()
    {
        $order_id = Yii::$app->request->getBodyParam('order_id');
        /**
         * @var Order
         */
        $order = Order::findOne(['id' => $order_id]);
        if (!$order || !$order->is_active) {
            return new ResponseContainer(404, 'Заявка не найдена');
        }
        if ($order->author_id != $this->user->id) {
            return new ResponseContainer(400, 'Вы не можете создавать отзывы на не свои заказы');
        }
        $mech_id = Yii::$app->request->getBodyParam('mech_id');
        $mech = User::findOne(['id' => $mech_id]);
        if (!$mech) {
            return new ResponseContainer(404, 'СТО/Магазин не найден');
        }

        $validMech = false; // Учавствовал ли СТО/Магазин в заявке
        foreach ($order->offers as $offer) {
            if ($offer->author_id == $mech_id) {
                $validMech = true;
            }
        }
        if (!$validMech) {
            return new ResponseContainer(400, 'Этот СТО/Магазин не участвовал в этом заказе');
        }

        $review = new Review();
        $review->setScenario('api-create');
        if ($review->load(Yii::$app->request->getBodyParams(), '')
            && $review->save()) {
            return new ResponseContainer(200, 'OK', $review->safeAttributes);
        }

        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $review->errors);
    }

    /**
     * @apiName actionUpdate
     * @apiGroup Review
     * @apiDescription Создание отзыва к СТО/Магазину
     *
     * @api {post} api/v3/review/update Обновление отзыва
     *
     * @apiParam {Object} Review Отзыв
     * @apiParam {Number} Review.id ID отзыва
     * @apiParam {String} Review.comment Текст отзыва
     * @apiParam {Number} Review.rating Оценка от 1 до 10
     *
     * @apiSuccess {Object} Review Отзыв
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": 200,
     *       "message": "OK",
     *       "data": Review
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
    public function actionUpdate()
    {
        $review_id = Yii::$app->request->getBodyParam('id');
        /**
         * @var Review
         */
        $review = Review::findOne(['id' => $review_id]);
        if (!$review) {
            return new ResponseContainer(404, 'Отзыв не найден '.$review_id);
        }
        if ($review->author_id != $this->user->id) {
            return new ResponseContainer(400, 'Вы не можете создавать отзывы на не свои заказы');
        }

        $review->setScenario('api-update');
        $review->load(Yii::$app->request->getBodyParams());

        if ($review->save()) {
            return new ResponseContainer(200, 'OK', $review->safeAttributes);
        }

        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $review->errors);
    }
}
