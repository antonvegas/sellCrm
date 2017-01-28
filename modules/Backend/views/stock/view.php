<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\Stock */

$this->title = $model->product->name;
$this->params['breadcrumbs'][] = ['label' => 'Склад', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--<p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'article',
            [
                'attribute' => 'product.url_image',
                'value' => empty($model->product->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'].$model->product->url_image,
                'format' => ['image',['height'=>'100']],
            ],
            'product.name',
            'product.category.name',
            [
                'attribute' => 'count',
                'value' => $model->count.' шт.',
            ],
            [
                'attribute' => 'priceIn',
                'value' => $model->priceIn.' руб.',
            ],
            [
                'attribute' => 'priceOut',
                'value' => $model->priceIn.' руб.',
            ],
        ],
    ]) ?>

</div>
