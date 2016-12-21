<?php

namespace app\modules\partner\controllers;

use Yii;
use app\models\Offer;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\PushHelper;

/**
 * OfferController implements the CRUD actions for Offer model.
 */
class OfferController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Creates a new Offer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id Order ID
     *
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Offer();

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            PushHelper::send(
                $model->order->author->profile->gcm_id,
                'Новое предложение по вашему заказу!',
                ['type' => PushHelper::TYPE_OFFER, 'order_id' => $model->order->id, 'cat' => $model->order->category_id]
            );

            return $this->redirect(['/admin/order/view', 'id' => $id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'orderId' => $id,
            ]);
        }
    }

    public function actionFindUser($query)
    {
        $result = '';
        $model = \app\models\User::find()
            ->where(['login' => $query])
            ->with('profile')
            ->asArray()
            ->one();
        if ($model) {
            $result = \yii\helpers\Json::encode($model);
        }

        return $result;
    }

    /**
     * Deletes an existing Offer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Offer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Offer the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Offer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
