<?php
/**
 * Created by PhpStorm.
 * User: Anton Vegas
 * Date: 11.10.2016
 * Time: 17:28
 */

namespace app\modules\Backend\controllers;
use Yii;

use app\modules\Backend\models\Stock;
use app\modules\Backend\models\Purchase;
use app\modules\Backend\models\ProductOfPurchase;
use app\modules\Backend\models\search\PurchaseSearch;
use yii\web\Controller;

class PurchaseController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new PurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd(){
        $model = new Purchase();
        $post = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post()) && $model->save() && isset($post['ProductOfPurchase']) && !empty($post['ProductOfPurchase'])) {

            $productsOfPurchase = $this->processProductOfPurchase($post['ProductOfPurchase'], $model);
            return $this->redirect('index');
        }

        return $this->render('create', ['model' => $model, 'productsOfPurchase' => isset($productsOfPurchase) ? $productsOfPurchase : []]);

    }

    public function actionView($id){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Purchase::findOne($id)) !== null) {
            return $model;
        } else { 
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function processProductOfPurchase($values, Purchase $model){
        foreach ($values as $value){

            $productOfItem = new ProductOfPurchase();

            $productOfItem->purchase_id = $model->id;
            $productOfItem->product_id = $value['product_id'];
            $productOfItem->count = $value['count'];
            $productOfItem->priceIn = $value['priceIn'];
            $productOfItem->priceOut = $value['priceOut']; 
            $productOfItem->article = $value['article'];

            if($productOfItem->validate()){
                $productOfItem->save();
                Stock::updateOrCreateItemOfStock($productOfItem->attributes);
            }else{
                echo '<pre>',print_r($productOfItem->getErrors()),'</pre>';
            }
        }


    }


}