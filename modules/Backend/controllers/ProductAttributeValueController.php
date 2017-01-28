<?php

namespace app\modules\Backend\controllers;

use Yii;
use app\modules\Backend\models\ProductAttributeValue;
use app\modules\Backend\models\search\ProductAttributeValueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductAttributeValueController implements the CRUD actions for ProductAttributeValue model.
 */
class ProductAttributeValueController extends Controller
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
     * Lists all ProductAttributeValue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductAttributeValueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductAttributeValue model.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return mixed
     */
    public function actionView($product_id, $attribute_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($product_id, $attribute_id),
        ]);
    }

    /**
     * Creates a new ProductAttributeValue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductAttributeValue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_id' => $model->product_id, 'attribute_id' => $model->attribute_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductAttributeValue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return mixed
     */
    public function actionUpdate($product_id, $attribute_id)
    {
        $model = $this->findModel($product_id, $attribute_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_id' => $model->product_id, 'attribute_id' => $model->attribute_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductAttributeValue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return mixed
     */
    public function actionDelete($product_id, $attribute_id)
    {
        $this->findModel($product_id, $attribute_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductAttributeValue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $product_id
     * @param integer $attribute_id
     * @return ProductAttributeValue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_id, $attribute_id)
    {
        if (($model = ProductAttributeValue::findOne(['product_id' => $product_id, 'attribute_id' => $attribute_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
