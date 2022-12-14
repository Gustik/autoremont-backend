<?php

namespace app\modules\api\controllers\v1;

use Yii;
use app\helpers\ResponseContainer;
use app\helpers\PushHelper;
use app\models\Variable;
use yii\filters\VerbFilter;
use app\models\Call;
use app\models\Order;

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

    //Client Actions
    public function actionClientCreate()
    {
        $order = new Order();
        $order->setScenario('api-create');
        if ($order->load(Yii::$app->request->getBodyParams())) {
            $order->city_id = $this->user->profile->city_id;
            if ($order->save()) {
                $topic = "/topics/{$order->city_id}-{$order->category->topic}";
                if (Variable::getParam('environment') == 'DEV') {
                    $topic .= '-dev';
                }
                PushHelper::send($topic, "{$order->category->name}: новый заказ");
                // Отправка пушей на старые топики для обратней совместимости.
                // УБРАТЬ В НОВОЙ ВЕРСИИ
                PushHelper::send("/topics/{$order->category->topic}", "{$order->category->name}: новый заказ");

                return new ResponseContainer(200, 'OK', $order->safeAttributes);
            }
        }

        return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
    }

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
                $flag = true;
                foreach ($order->calls as $call) {
                    if ($call->client_accept) {
                        $flag = false;
                    }
                }
                $order->new_calls = 0;
                if ($flag) {
                    foreach ($order->calls as $call) {
                        ++$order->new_calls;
                        $call->setScenario('api-view');
                        $call->executor->setScenario('api-view');
                    }
                    $order->setScenario('api-view');
                }
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
        $phones[] = '+79248664565'; // Миша
        $phones[] = '+79142888877'; // Прокопий

        $rand_key = array_rand($phones, 1);
        $phone = $phones[$rand_key];

        return new ResponseContainer(200, 'OK', ['login' => $phone]);
        /*
        $order = Order::findOne($id);
        if ($order) {
            if ($order->author_id != $this->user->id) {
                if (!$order->executor_id) {
                    //Create call
                    $call = new Call();
                    $call->client_id = $order->author->id;
                    $call->mech_id = $this->user->id;
                    $call->order_id = $order->id;
                    if ($call->save()) {
                        return new ResponseContainer(200, 'OK', ['login' => $order->author->login], 0, ['call_id' => $call->id]);
                    }
                    return new ResponseContainer(500, 'Внутренняя ошибка сервера', $call->errors);
                }
                return new ResponseContainer(403, 'Заявка уже принята');
            }
            return new ResponseContainer(403, 'Невозможно принять собственную заявку');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
        */
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
    public function actionView($id)
    {
        $order = Order::find()->where(['id' => $id])->with('calls', 'executor', 'author', 'calls.client', 'calls.executor')->one();
        if ($order && $order->is_active) {
            $order->setScenario('api-view-without-calls');
            if ($order->executor_id == $this->user->id) {
                $order->author->setScenario('api-view');
            } else {
                unset($order->author);
            }
            if ($order->executor) {
                $order->executor->setScenario('api-view');
            }
            $flag = true;
            foreach ($order->calls as $call) {
                if ($call->client_accept) {
                    $flag = false;
                }
            }
            $order->new_calls = 0;
            if ($flag) {
                foreach ($order->calls as $call) {
                    ++$order->new_calls;
                    $call->setScenario('api-view');
                    $call->executor->setScenario('api-view');
                    $call->client->setScenario('api-view');
                    $call->executor->setScenario('api-view');
                }
                $order->setScenario('api-view');
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
                    if ($type == 'client') {
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
