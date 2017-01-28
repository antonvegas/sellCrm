<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\ProductAttributeValue */

$this->title = 'Create Product Attribute Value';
$this->params['breadcrumbs'][] = ['label' => 'Product Attribute Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-value-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
