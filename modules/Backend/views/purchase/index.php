<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Закупки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Html::a('Новая закупка', ['add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12',
        'columns' => [
            [
                'contentOptions' => ['class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'],
                'attribute' => 'created_at',
                'format' => 'text',
            ],
            'sellerName',
            [
                'contentOptions' => ['class' => 'col-xs-2 col-sm-2 col-md-2 col-lg-2'],
                'attribute' => 'price',
                'format' => 'text',
                'value' => function($model){
                    return $model->price.' руб.';
                }
            ],
            [ 
                'contentOptions' => ['class' => 'col-xs-1 col-sm-1 col-md-1 col-lg-1'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],

        ]
    ]); ?>
</div>
