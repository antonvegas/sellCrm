<?php

namespace app\modules\Settings\controllers;

use Yii;

use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }



    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
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
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();


        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');

        $user = new \app\models\Users();

        $user->setScenario('insert');

        if(Yii::$app->request->post('Users')){

            if ($user->load(Yii::$app->request->post())) {

                if ($user->validate()){
                    $user->save();

                    \Yii::$app->getSession()->setFlash('success', 'Пользователь добавлен');

                    if(Yii::$app->request->post('roles')){

                        Yii::$app->authManager->revokeAll($user->getId());

                        foreach(Yii::$app->request->post('roles') as $role)
                        {
                            $new_role = Yii::$app->authManager->getRole($role);
                            Yii::$app->authManager->assign($new_role, $user->getId());
                        }
                    }


                    return $this->redirect(['view', 'id' => $user->id]);
                }
            }
        }


        return $this->render('create', [
            'model' => $user,
            'roles' => $roles,
            'user_permission' => [],
            'moduleName' => Yii::$app->controller->module->id
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $user->setScenario('update');

        if(Yii::$app->request->post('Users')){

            if ($user->load(Yii::$app->request->post())) {
                //echo '<pre>',print_r($user->password),'</pre>';
                //echo '<pre>',print_r($user->password_initial),'</pre>';
                if(!empty($user->password) && $user->password != $user->password_initial) {
                    $user->setScenario('updateWithPassword');
                }else{
                    $user->setScenario('update');
                }

                if ($user->validate()){

                    $user->save();

                    $user->password = $user->password_repeat = null;

                    \Yii::$app->getSession()->setFlash('success', 'Пользователь обновлен');
                }
            }
        }

        if(Yii::$app->request->post('roles')){
            Yii::$app->authManager->revokeAll($user->getId());

            foreach(Yii::$app->request->post('roles') as $role)
            {
                $new_role = Yii::$app->authManager->getRole($role);

                Yii::$app->authManager->assign($new_role, $user->getId());
            }
        }


        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
        $user_permission = array_keys(Yii::$app->authManager->getRolesByUser($id));


        return $this->render('update', [
            'id' => $user->getId(),
            'model' => $user,
            'roles' => $roles,
            'user_permission' => $user_permission,
            'moduleName' => Yii::$app->controller->module->id
        ]);

    }

    /**
     * Deletes an existing Users model.
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
