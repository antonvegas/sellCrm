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
    'filterUrl' => '/backend/product/grid-for-popup-sale',
    'id' => 'gridPopupId',
    'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12',
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Изображение',
            'format' => ['image', ['height' => '70']],
            'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center'],
            'value' => function ($model) {
                return empty($model->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'] . $model->url_image;
            }
        ],
        [
            'label' => 'Категория',
            'value' => 'category.name',
            'format' => 'raw',
            'contentOptions' => ['class' => 'col-xs-4 col-sm-4 col-md-3 col-lg-3'],
        ],
        [
            'attribute' => 'name',
            'contentOptions' => ['class' => 'col-xs-4 col-sm-4 col-md-3 col-lg-3'],
            'format' => 'raw',
            'value' => function ($model) {

                if (empty($model->count)) {
                    return $model->name;
                } else {
                    return $model->name . ' <small>' . Html::a('[выбрать]', '#', ['class' => 'putItemToBasket', 'data-productId' => $model->id, 'data-name' => $model->name, 'data-count' => 1, 'data-max' => $model->count,/* 'data-price' => $model*/]) . '</small>';
                }
            },

        ],
        [
            'label' => 'Цена',
            'format' => 'raw',
            'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-2 col-lg-2'],
            'value' => function ($model) {
                return ;//isset($model->productOfPurchases->priceOut) ? $model->productOfPurchases->priceOut .' руб.' : 'не задано';
            },
        ],
        [
            'label' => 'Кол-во',
            'format' => 'raw',
            'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-2 countValueInBlockChoose'],
            'value' => function ($model) {
                $content = '';
                if ($model->count > 0)
                    $content = '
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'
                        . Html::input('text', 'count', 1, ['class' => 'form-control text-center', 'data-max' => $model->count]) .
                        '</div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 5px; padding:0px;">
                                <small>(Макс.<mark>' . $model->count . '</mark>)</small>
                            </div>
                        ';
                else
                    $content = '<div style="color:#FF5847;" class="text-center"> <small>Нет на складе</small></div>';
                return $content;
            },
        ],
    ],
]);

Pjax::end();


?>
