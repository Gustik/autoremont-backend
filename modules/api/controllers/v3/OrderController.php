<?php
namespace app\modules\api\controllers\v3;

use Yii;

use app\helpers\ResponseContainer;
use app\helpers\PushHelper;
use app\models\Variable;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use app\models\Call;
use app\models\Order;
use app\models\Offer;

class OrderController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'create' => ['post'],
                'update' => ['post'],
            ],
        ];
        return $behaviors;
    }

    /**
     * @apiName actionClientCreate
     * @apiGroup Order
     * @apiDescription Создание заказа клиентом
     * @api {post} api/v3/order/client-create Создание заказа
     *
     * @apiParam {Object} Order Заказ
     * @apiParam {String} Order.description Описание требуемых работ к выполнению или название запчасти
     * @apiParam {Number} Order.category_id Категория (1 - ремонт, 2 - запчасти)
     * @apiParam {String} [Order.car_brand] Марка машиный
     * @apiParam {String} [Order.car_model] Модель машины
     * @apiParam {String} [Order.car_color] Цвет машины
     * @apiParam {String} [Order.tagNames] Список тегов через запятую (Ходовка, Электрика, итп), если указать не существующий тег, то он создастся
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": "200",
     *       "message": "OK",
     *       "data": Order
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionClientCreate()
    {
        $order = new Order();
        $order->setScenario('api-create');
        if ($order->load(Yii::$app->request->getBodyParams())) {
            $order->city_id = $this->user->profile->city_id;
            if ($order->save()) {
                $topic = "/topics/{$order->city_id}-{$order->category->topic}";
                if (Variable::getParam('environment') == 'DEV') {
                    $topic .= "-dev";
                }
                PushHelper::send($topic, "{$order->category->name}: новый заказ", ['type' => PushHelper::TYPE_ORDER, 'order_id' => $order->id, "cat" => $order->category_id]);
                // Отправка пушей на старые топики для обратней совместимости.
                // УБРАТЬ В НОВОЙ ВЕРСИИ
                PushHelper::send("/topics/{$order->category->topic}", "{$order->category->name}: новый заказ");
                return new ResponseContainer(200, 'OK', $order->safeAttributes);
            }
        }
        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
    }

    /**
     * @apiName actionClientUpdate
     * @apiGroup Order
     * @apiDescription Обновление заказа клиентом
     * @api {post} api/v3/order/client-create Обновление заказа
     *
     * @apiParam {Object} Order Заказ
     * @apiParam {Number} Order.id ID заказа
     * @apiParam {String} Order.description Описание требуемых работ к выполнению или название запчасти
     * @apiParam {Number} Order.category_id Категория (1 - ремонт, 2 - запчасти)
     * @apiParam {String} [Order.car_brand] Марка машиный
     * @apiParam {String} [Order.car_model] Модель машины
     * @apiParam {String} [Order.car_color] Цвет машины
     * @apiParam {String} [Order.tagNames] Список тегов через запятую (Ходовка, Электрика, итп), если указать не существующий тег, то он создастся
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": "200",
     *       "message": "OK",
     *       "data": Order
     *     }
     *
     * @apiErrorExample {json} Ошибки:
     *     {
     *       "status": "400",
     *       "message": "Отсутствует обязательный параметр: id"
     *     }
     *     {
     *       "status": "403",
     *       "message": "Заявка принадлежит не вам"
     *     }
     *     {
     *       "status": "404",
     *       "message": "Заявка не найдена"
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionClientUpdate()
    {
        $id = Yii::$app->request->post('id');
        if ($id) {
            $order = Order::findOne($id);
            if ($order) {
                if ($order->author_id == $this->user->id) {
                    $order->setScenario('api-update');
                    if ($order->load(Yii::$app->request->getBodyParams()) && $order->save()) {
                        return new ResponseContainer(200, 'OK', $order->safeAttributes);
                    }
                    return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
                }
                return new ResponseContainer(403, 'Заявка принадлежит не вам');
            }
            return new ResponseContainer(404, 'Заявка не найдена');
        }
        return new ResponseContainer(400, 'Отсутствует обязательный параметр: id');
    }


    public function actionClientView($id)
    {
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionClientMy()
    {
        $orders = [];
        foreach ($this->user->orders as $order) {
            if ($order->is_active) {
                $order->setScenario('api-view-without-calls');
                if ($order->executor) {
                    $order->executor->setScenario('api-view');                    
                }
                $order->new_offers = $order->newOffers;
                $orders[] = $order->safeAttributes;
            }
        }
        return new ResponseContainer(200, 'OK', $orders);
    }

    public function actionClientReject($id)
    {
        $order = Order::findOne($id);
        if ($order) {
            if ($order->author_id == $this->user->id) {
                if (!$order->executor_id) {
                    $order->is_active = 0;
                    foreach ($order->calls as $call) {
                        $call->delete();
                    }
                    if ($order->save()) {
                        return new ResponseContainer(200, 'OK');
                    }
                    return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
                }
                return new ResponseContainer(403, 'Нельзя отменить уже принятую заявку');
            }
            return new ResponseContainer(403, 'Заявка принадлежит не вам');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionClientRaise($id, $value = 100)
    {
        $order = Order::findOne($id);
        if ($order) {
            if ($order->author_id == $this->user->id) {
                $order->price += $value;
                if ($order->save()) {
                    return new ResponseContainer(200, 'OK', ['price' => $order->price]);
                }
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
            }
            return new ResponseContainer(403, 'Заявка принадлежит не вам');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionClientAccept($id)
    {
        $this->redirect(['accept', 'id' => $id, 'type' => 'client']);
    }

    public function actionClientDecline($id)
    {
        $this->redirect(['decline', 'id' => $id, 'type' => 'client']);
    }

    public function actionClientDeclineAll($id)
    {
        $order = Order::findOne($id);
        if ($order) {
            if ($order->author_id == $this->user->id) {
                foreach ($order->calls as $call) {
                    $call->delete();
                }
                return new ResponseContainer();
            }
            return new ResponseContainer(403, 'Заявка принадлежит не вам');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    //Mech Actions
    public function actionMechView($id)
    {
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionMechIndex($id = 1)
    {
        $orderModels = Order::findFree($id)->all();
        $orders = [];
        foreach ($orderModels as $order) {
            $order->setScenario('api-view');
            $order->my_offer = $order->myOffer;
            $orders[] = $order->safeAttributes;
        }
        return new ResponseContainer(200, 'OK', $orders);
    }

    public function actionMechMy()
    {
        $orders = [];
        foreach ($this->user->acceptedOrders as $order) {
            if ($order->is_active) {
                $order->setScenario('api-view');
                $order->author->setScenario('api-view');
                $orders[] = $order->safeAttributes;
            }
        }
        return new ResponseContainer(200, 'OK', $orders);
    }

    public function actionMechCall($id)
    {
        /** @var $order Order */
        $order = Order::findOne($id);
        if ($order) {
            if ($order->author_id != $this->user->id) {
                if (!$order->executor_id) { // Если исполнитель не присвоен к заказу

                    if(!$this->user->can_work) // Если не может работать (не оплачен аккаунт)
                        return new ResponseContainer(200, 'OK', ['login' => 'need_payment']);

                    // Create offer
                    /** @var $offer Offer */
                    $offer = Offer::findProduce($id, $this->user->id);
                    if ($offer->isNewRecord) {
                        $offer->is_call = true;
                        $offer->text = 'Вам звонили';
                        $offer->save();
                        PushHelper::send($order->author->profile->gcm_id,
                            "Новое предложение по вашему заказу!",
                            ['type' => PushHelper::TYPE_OFFER, 'order_id' => $order->id]);
                    }
                    return new ResponseContainer(200, 'OK', ['login' => $order->author->login]);
                }
                return new ResponseContainer(403, 'Заявка уже принята');
            }
            return new ResponseContainer(403, 'Невозможно принять собственную заявку');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionMechAccept($id)
    {
        $this->redirect(['accept', 'id' => $id, 'type' => 'mech']);
    }

    public function actionMechDecline($id)
    {
        $this->redirect(['decline', 'id' => $id, 'type' => 'mech']);
    }

    //Common Actions

    /**
     * @apiName actionView
     * @apiGroup Order
     * @apiDescription Просмотр заказа
     * @api {get} api/v3/order/view?:id Просмотр заказа
     *
     * @apiParam {Number} id ID заказа
     *
     * @apiSuccess {Object} Order Объект заказа
     * @apiSuccess {String} Order.description Описание требуемых работ к выполнению или название запчасти
     * @apiSuccess {Number} Order.category_id Категория (1 - ремонт, 2 - запчасти)
     * @apiSuccess {String} Order.car_brand Марка машиный
     * @apiSuccess {String} Order.car_model Модель машины
     * @apiSuccess {String} Order.car_color Цвет машины
     * @apiSuccess {String} Order.tagNames Список тегов через запятую (Ходовка, Электрика, итп)
     * @apiSuccess {Object[]} Order.offers Список предложений к заказу (Offer)
     * @apiSuccess {String} Order.offers.created_at Дата создания преложения
     * @apiSuccess {String} Order.offers.text Текст предложения
     * @apiSuccess {Object} Order.offers.author Автор предложения (User)
     * @apiSuccess {Object} Order.offers.author.profile Профиль автора предложения (Profile)
     * @apiSuccess {Object} Order.executor Исполнитель заказа (User)
     * @apiSuccess {Object} Order.author Автор заказа (User)
     *
     * @apiSuccessExample {json} Успех:
     *     {
     *       "status": "200",
     *       "message": "OK",
     *       "data": Order
     *     }
     *
     * @apiErrorExample {json} Ошибки:
     *     {
     *       "status": "404",
     *       "message": "Заявка не найдена"
     *     }
     *
     * @apiVersion 3.0.0
     *
     * @return ResponseContainer
     */
    public function actionView($id)
    {
        $order = Order::find()->where(['id' => $id])->with('offers', 'offers.author', 'offers.author.profile', 'executor', 'author')->one();
        if ($order && $order->is_active) {
            $order->new_offers = $order->newOffers;
            $order->setScenario('api-view');
            if ($order->author_id == $this->user->id) {
                // If user is author show all offers and mark it read
                foreach ($order->offers as $offer) {
                    $offer->setScenario('api-view');
                    $offer->author->setScenario('api-view');
                    $offer->author->profile->setScenario('api-view-lite');
                }
                $order->readAllOffers();
                $order->author->setScenario('api-view');
            } else if ($order->executor_id == $this->user->id) {
                // If user is executor show my offer and author
                foreach ($order->offers as $offer) {
                    $offer->setScenario('api-view');
                    $offer->author->setScenario('api-view');
                    $offer->author->profile->setScenario('api-view-lite');
                }
                $order->author->setScenario('api-view');
                $order->my_offer = $order->myOffer;
            } else {
                // If user is not both show my offer and unset author
                unset($order->author);
                unset($order->offers);
                $order->my_offer = $order->myOffer;
            }
            if ($order->executor) {
                $order->executor->setScenario('api-view');
            }
            return new ResponseContainer(200, 'OK', $order->safeAttributes);
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionAccept($id, $type)
    {
        $call = Call::findOne($id);
        if ($call) {
            $order = $call->order;
            if ($this->user->canAcceptCall($call, $type)) {
                $field = "{$type}_accept";
                $call->$field = 1;
                if ($call->save()) {
                    if ($call->client_accept && $call->mech_accept) {
                        $order->executor_id = $call->mech_id;
                        if ($order->save()) {
                            foreach ($order->calls as $call) {
                                $call->delete();
                            }
                            return new ResponseContainer();
                        }
                        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
                    }
                    if ($type == "client") {
                        foreach ($order->calls as $c) {
                            if ($c->id != $call->id) {
                                $c->delete();
                            }
                        }
                    }
                    return new ResponseContainer();
                }
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $call->errors);
            }
            return new ResponseContainer(403, 'Вы не можете принять эту заявку');
        }
        return new ResponseContainer(404, 'Звонок не найден');
    }

    public function actionDecline($id, $type)
    {
        $call = Call::findOne($id);
        if ($call) {
            $order = $call->order;
            if ($this->user->canDeclineCall($call, $type)) {
                $call->delete();
                return new ResponseContainer();
            }
            return new ResponseContainer(403, 'Вы не можете отклонить этот звонок');
        }
        return new ResponseContainer(404, 'Звонок не найден');
    }
}
