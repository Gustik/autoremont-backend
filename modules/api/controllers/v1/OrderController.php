<?php
namespace app\modules\api\controllers\v1;

use Yii;

use app\helpers\ResponseContainer;
use yii\filters\VerbFilter;

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
        $this->redirect('view', ['id' => $id]);
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
                if ($order->save()) {
                    return new ResponseContainer(200, 'OK');
                }
                return new ResponseContainer(500, 'Внутренняя ошибка сервера', $order->errors);
            }
            return new ResponseContainer(403, 'Заявка принадлежит не вам');
        }
        return new ResponseContainer(404, 'Заявка не найдена');
    }

    //Mech Actions
    public function actionMechView($id)
    {
        $this->redirect('view', ['id' => $id]);
    }

    public function actionMechIndex()
    {
        $orderModels = Order::findAll(['executor_id' => null]);
        $orders = [];
        foreach ($orderModels as $order) {
            if ($order->is_active) {
                $order->setScenario('api-view');
                $orders[] = $order->safeAttributes;
            }
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
        $order = Order::findOne(['id' => $id]);
	if ($order) {
	    return new ResponseContainer(200, 'OK', ['phone' => $order->author->login]);
        }
        return new ResponseContainer(404, 'Заявка не найдена');
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
}
