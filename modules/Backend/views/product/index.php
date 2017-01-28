<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\Backend\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Backend\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Изображение',
                'format'=> ['image',['height'=>'100']],
                'contentOptions' => ['class' => 'col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center'],
                'value' =>  function($model){
                    return empty($model->url_image) ? '/images/not-available.png' : Yii::$app->params['productImagePath'].$model->url_image;
                }
            ],
            [
                'attribute' => 'category_id',
                'filter' => Category::find()->select(['IF(category.parent_id IS NOT NULL, CONCAT(c2.name, "->", category.name) , category.name) as name','category.id'])->leftJoin('category c2', 'category.parent_id = c2.id')->indexBy('id')->orderBy('name')->column(),
                'value' => 'category.name'
            ],
            'name',
            [
                'attribute' => 'active',
                'filter' => [0 => 'Нет', 1 => 'Да'],
                'format' => 'boolean'
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
