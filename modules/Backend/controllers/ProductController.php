<?php

namespace app\modules\Backend\controllers;

use Yii;

use app\modules\Backend\models\ProductAttribute;
use app\modules\Backend\models\ProductAttributeValue;
use app\modules\Backend\models\Product;
use app\modules\Backend\models\Stock;
use app\modules\Backend\models\search\ProductSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    { 
        
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->searchIndex(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $article
     * @return bool|null
     */
    public function actionGetItemByArticle($article){
        $items = [];

        if(!isset($article) && empty($article)){
            return false;
        }

        $result = Stock::find()->where(['article' => $article])->all();

        foreach ($result as $val){
            $items[$val->id] = [
                'id' => $val->id, 
                'article' => $val->article,
                'category_id' => $val->product->category_id,
                'name' => $val->product->name,
                'active' => $val->product->active,
                'product_id' => $val->product_id,
                'count' => $val->count,
                'url_image' => empty($val->product->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'].$val->product->url_image,
                'price' => $val->priceOut,
            ];
        }

        return json_encode(["count" => count($items),"products" => $items]);

    }

    public function actionGridForPopupPurchase(){
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->searchIndex(Yii::$app->request->queryParams);

            return $this->renderAjax('grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $valuesAttributes = $this->initAttributeValues($model);

        $post = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post()) && $model->save() && Model::loadMultiple($valuesAttributes, $post)) {
            $this->processAttributeValues($valuesAttributes, $model);
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'values' => $valuesAttributes
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $valuesAttributes = $this->initAttributeValues($model);

        $post = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post()) && $model->save() && Model::loadMultiple($valuesAttributes, $post)) {

            $this->processAttributeValues($valuesAttributes, $model);
            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'values' => $valuesAttributes
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function initAttributeValues(Product $model){
        $valuesAttributes = $model->getProductAttributeValue()->indexBy('attribute_id')->all();
        $allAttributes = ProductAttribute::find()->indexBy('id')->all();

        foreach(array_diff_key($allAttributes, $valuesAttributes) as $item){
            $valuesAttributes[$item->id] = new ProductAttributeValue(['attribute_id' => $item->id]);
        }

        foreach ($valuesAttributes as $valuesAttributesItem){
            $valuesAttributesItem->setScenario(ProductAttributeValue::SCENARIO_TABULAR);
        }

        return $valuesAttributes;
    }


    private function processAttributeValues($values, Product $model){
        foreach ($values as $value){
            $value->product_id = $model->id;

            if($value->validate()){
                if(!empty($value->value) || strlen($value->value) > 0){
                    $value->save();
                }else{
                    $value->delete();
                }
            }
        }
    }


}

