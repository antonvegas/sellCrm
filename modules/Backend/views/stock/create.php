<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\Stock */

$this->title = 'Добавление элемента на склад';
$this->params['breadcrumbs'][] = ['label' => 'Склад', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
