<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\Profile;
use app\modules\admin\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
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
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $user = new User();
        $profile = new Profile();
        $user->setScenario('admin-create');
        $profile->setScenario('admin-create');

        if ($password = Yii::$app->request->post('User[password]')) {
            $user->password = $password;
        }
        $profile->crop_info = Yii::$app->request->post('crop_info');
        $validate = false;
        if ($user->load(Yii::$app->request->post(), 'User') && $user->validate() &&
            $profile->load(Yii::$app->request->post(), 'Profile') && $profile->validate()) {
            $validate = true;
        }
        if ($validate) {
            if ($profile->company_logo_image = UploadedFile::getInstanceByName('company_logo_image')) {
                $profile->company_logo = 'logo_'.time().'.'.$profile->company_logo_image->getExtension();
            }
            $user->save();
            $profile->user_id = $user->id;
            $profile->save();

            return $this->redirect(['view', 'id' => $user->id]);
        } else {
            return $this->render('create', [
                'user' => $user,
                'profile' => $profile,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $profile = $user->profile;
        $user->setScenario('admin-update');
        $profile->setScenario('admin-update');

        if ($password = Yii::$app->request->post('User[password]')) {
            $user->password = $password;
        }
        $profile->crop_info = Yii::$app->request->post('crop_info');
        $validate = false;
        if ($user->load(Yii::$app->request->post(), 'User') && $user->validate() &&
            $profile->load(Yii::$app->request->post(), 'Profile') && $profile->validate()) {
            $validate = true;
        }
        if ($validate) {
            if ($profile->company_logo_image = UploadedFile::getInstanceByName('company_logo_image')) {
                @unlink(Yii::getAlias('@webroot/img/upload/company-logo/').$profile->company_logo);
                $profile->company_logo = 'logo_'.time().'.'.$profile->company_logo_image->getExtension();
            }

            $user->save();
            $profile->save();

            return $this->redirect(['view', 'id' => $user->id]);
        } else {
            return $this->render('update', [
                'user' => $user,
                'profile' => $profile,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //Prevent deleting "admin"
        if ($id != 1) {
            $model->is_active = 0;
            $model->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
