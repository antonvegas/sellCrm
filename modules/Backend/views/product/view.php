<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'category_id',
                'value' => \yii\helpers\ArrayHelper::getValue($model, 'category.name')
            ],
            'name',
            'description:ntext',
            'active:boolean',
            [
                'attribute' => 'url_image',
                'value' => empty($model->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'].$model->url_image,
                'format' => ['image',['height'=>'100']],
            ]


        ],
    ]) ?>


    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $model->getProductAttributeValue()->with('productAttribute')]),
        'columns' => [
            [
                'attribute' => 'attribute_id',
                'value' => 'productAttribute.name'
            ],
            'value',
        ],
    ]); ?>

</div>
