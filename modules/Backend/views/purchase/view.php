<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\Product */

$this->title = 'Закупка от '.$model->created_at;
$this->params['breadcrumbs'][] = ['label' => 'Закупки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            [
                'attribute' => 'price',
                'value' => $model->price.' руб.',
            ]

        ],
    ]) ?>

    <h1><?= 'Товары по накладной' ?></h1>
    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getProductOfPurchases()->with('product')]),
        'columns' => [
            'article',
            'product.name',
            [
                'label' => 'Категория',
                'value' => function($e){
                    return $e->product->category->name;
                }
            ],
            [
                'attribute' => 'count',
                'value' => function($e){
                    return $e->count.' шт.';
                }
            ],
            [
                'attribute' => 'priceIn',
                'value' => function($e){
                    return $e->priceIn.' руб.';
                }
            ],
            [
                'attribute' => 'priceOut',
                'value' => function($e){
                    return $e->priceOut.' руб.';
                }
            ],
        ],
    ]); ?>
</div>
