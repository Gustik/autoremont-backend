<?php
namespace app\modules\api\controllers\v1;

use Yii;

use app\helpers\ResponseContainer;
use yii\filters\VerbFilter;
use yii\helpers\Json;

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
        if ($order->load(Yii::$app->request->getBodyParams()) && $order->save()) {
            return new ResponseContainer(200, 'OK', $order->safeAttributes);
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
                $order->setScenario('api-view');
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
                $order->is_active = 0;
                foreach ($order->calls as $call) {
                    $call->delete();
                }
                if ($order->save()) {
                    return new ResponseContainer(200, 'OK');
                }
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
            }
            return new ResponseContainer(403, 'Заявка принадлежит не вам');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionClientRaise($id, $value = 10)
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
        $this->redirect(['accept', 'id' => $id, $type => 'client']);
    }

    //Mech Actions
    public function actionMechView($id)
    {
        $this->redirect(['view', 'id' => $id]);
    }

    public function actionMechIndex()
    {
        $orderModels = Order::findFree()->all();
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
                $orders[] = $order->safeAttributes;
            }
        }
        return new ResponseContainer(200, 'OK', $orders);
    }

    public function actionMechCall($id)
    {
        $order = Order::findOne($id);
        if ($order) {
            //Create call
            $call = new Call();
            $call->client_id = $order->author->id;
            $call->mech_id = $this->user->id;
            $call->order_id = $order->id;
            if ($call->save()) {
                $gcm = Yii::$app->gcm;

                //Send push-notification to client
                $message = [
                    'text' => "Вам звонили с номера {$this->user->login}. Вы договорились?",
                    'call_id' => $call->id
                ];
                $gcm->send($order->author->profile->gcm_id, Json::encode($message));

                //Send push-notification to mech
                $message = [
                    'text' => "Вы звонили клиенту {$order->author->login}. Вы договорились?",
                    'call_id' => $call->id
                ];
                $gcm->send($this->user->profile->gcm_id, Json::encode($message));

                return new ResponseContainer(200, 'OK', ['phone' => $order->author->login]);
            }
            return new ResponseContainer(500, 'Внутренняя ошибка сервера', $call->errors);
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    public function actionMechAccept($id)
    {
        $this->redirect(['accept', 'id' => $id, $type => 'mech']);
    }

    //Common Actions
    public function actionView($id)
    {
        $order = Order::findOne($id);
        if ($order && $order->is_active) {
            $order->setScenario('api-view');
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
                    return new ResponseContainer();
                }
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $call->errors);
            }
            return new ResponseContainer(403, 'Вы не можете принять эту заявку');
        }
        return new ResponseContainer(404, 'Звонок не найден');
    }
}
