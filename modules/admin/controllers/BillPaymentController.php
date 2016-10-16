<?php

namespace app\modules\admin\controllers;

use app\models\BillAccount;
use app\models\User;
use Exception;
use Yii;
use app\models\BillPayment;
use app\modules\admin\models\BillPaymentSearch;
use app\modules\admin\controllers\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BillPaymentController implements the CRUD actions for BillPayment model.
 */
class BillPaymentController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all BillPayment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BillPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BillPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BillPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotAcceptableHttpException
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new BillPayment();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
                $transaction->rollback();
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            if(!$model->save()){
                throw new Exception('Ошибка создания платежа');
            }

            $account = BillAccount::find()->where(['=', 'user_id', $model->user_id])->one();

            if(!$account) $account = new BillAccount();

            $account->user_id = $model->user_id;
            $account->days += $model->days;

            if(!$account->save()){
                throw new Exception('Ошибка обновления аккаунтинга');
            }

            $user = User::findOne($model->user_id);
            if(!$user->can_work) {
                $user->can_work = true;
                if(!$user->save()){
                    throw new Exception('Ошибка активации аккаунта');
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Запрос на взнос успешно создан, ожидайте подтверждения');
            return $this->redirect(['index']);

        } catch(Exception $e) {
            $transaction->rollback();
            throw new NotAcceptableHttpException($e->getMessage());
        }
    }

    /**
     * Updates an existing BillPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BillPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BillPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BillPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BillPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
