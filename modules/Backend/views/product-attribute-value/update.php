<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\ProductAttributeValue */

$this->title = 'Update Product Attribute Value: ' . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Attribute Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_id, 'url' => ['view', 'product_id' => $model->product_id, 'attribute_id' => $model->attribute_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-attribute-value-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
