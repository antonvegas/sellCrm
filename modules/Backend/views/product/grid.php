<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use app\modules\Backend\models\Category;


Pjax::begin(['id' => 'gridPopupId', 'enablePushState' => false]);

$dataProvider->setPagination(['pageSize' => 5]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'filterUrl' => '/backend/product/grid-for-popup-purchase',
    'id' => 'gridPopupId',
    'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Изображение',
            'format'=> ['image',['height'=>'70']],
            'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center'],
            'value' =>  function($model){
                return empty($model->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'].$model->url_image;
            }
        ],
        [
            'attribute' => 'category_id',
            'filter' => Category::find()->select(['IF(category.parent_id IS NOT NULL, CONCAT(c2.name, "->", category.name) , category.name) as name','category.id'])->leftJoin('category c2', 'category.parent_id = c2.id')->indexBy('id')->orderBy('name')->column(),
            'value' => 'category.name',
            'contentOptions' => ['class' => 'col-xs-4 col-sm-4 col-md-3 col-lg-3'],
        ],
        [
            'attribute' => 'name',
            'contentOptions' => ['class' => 'col-xs-8 col-sm-8 col-md-8 col-lg-8'],
            'format'=>'raw',
            'value' => function($model) {
                return $model->name.' <small>'.Html::a('[выбрать]', '#', ['class' => 'putItemToBasket', 'data-productId' => $model->id, 'data-name' => $model->name]).'</small>';
            },

        ]
    ],
]);

Pjax::end();


?>


