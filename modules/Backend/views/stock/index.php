<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\modules\Backend\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Backend\models\search\StockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Склад';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Изображение',
                'format'=> ['image',['height'=>'100']],
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center'],
                'value' =>  function($model){
                    return empty($model->product->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'].$model->product->url_image;
                }
            ],
            [
                'attribute' => 'article',
                'contentOptions' => ['class' => 'col-xs-4 col-sm-3 col-md-3 col-lg-3'],
            ],
            [
                'attribute' => 'categoryId',
                'filter' => Category::find()->select(['IF(category.parent_id IS NOT NULL, CONCAT(c2.name, "->", category.name) , category.name) as name','category.id'])->leftJoin('category c2', 'category.parent_id = c2.id')->indexBy('id')->orderBy('name')->column(),
                'value' => 'product.category.name',
                'contentOptions' => ['class' => 'col-xs-4 col-sm-3 col-md-3 col-lg-3'],
            ],
            [
                'attribute' => 'productName',
                'contentOptions' => ['class' => 'col-xs-4 col-sm-3 col-md-3 col-lg-3'],
            ],
            [
                'attribute' => 'totalOfCount',
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'value' =>  function($model){
                    return $model->totalOfCount == 0 ? 'Нет на складе' : 'Всего: '.$model->totalOfCount.' шт.';
                }
            ],
            [
                'attribute' => 'priceIn',
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'value' =>  function($model){
                    return $model->priceIn.' руб.';
                }
            ],
            [
                'attribute' => 'priceOut',
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'value' =>  function($model){
                    return $model->priceOut.' руб.';
                }
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
