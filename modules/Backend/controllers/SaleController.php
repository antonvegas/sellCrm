<?php
/**
 * Created by PhpStorm.
 * User: Anton Vegas
 * Date: 11.10.2016
 * Time: 17:28
 */

namespace app\modules\Backend\controllers;
use Yii;
use app\modules\Backend\models\Sale;
use app\modules\Backend\models\ProductOfSale;
use app\modules\Backend\models\Stock;
use app\modules\Backend\models\search\SaleSearch;
use yii\web\Controller;

class SaleController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new SaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd(){
        
        $model = new Sale();
        $post = Yii::$app->request->post();

        if ($model->load(Yii::$app->request->post()) && isset($post['ProductOfSale']) && !empty($post['ProductOfSale'])) {
            
            if($model->save()){
                $this->processProductOfSale($post['ProductOfSale'], $model);
                return $this->redirect('index');
            }
            else{
                var_dump($model->getErrors());
            }
        }

        return $this->render('add', ['model' => $model]);

    }


    private function processProductOfSale($values, Sale $model){
        foreach ($values as $value){

            $productOfItem = new ProductOfSale();

            $productOfItem->sale_id = $model->id;
            $productOfItem->product_id = $value['product_id'];
            $productOfItem->stock_id = $value['stock_id'];
            $productOfItem->price = $value['price'];
            $productOfItem->count = $value['count'];
            $productOfItem->article = $value['article'];
 
            if($productOfItem->validate()){
                $productOfItem->save();
                Stock::reduceOfCountOfItemById($productOfItem->stock_id, $productOfItem->count);
            }
        }


    }


}