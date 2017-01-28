<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\Backend\models\Category;

/* @var $this yii\web\View */
/* @var $model app\modules\Backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6">

            <?= $form->field($model, 'category_id')->dropDownList(Category::find()->select(['IF(category.parent_id IS NOT NULL, CONCAT(c2.name, "->", category.name) , category.name) as name','category.id'])->leftJoin('category c2', 'category.parent_id = c2.id')->indexBy('id')->orderBy('name')->column()) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?> 
 
            <?= $form->field($model, 'active')->dropDownList([1 => 'Да', 0 => 'Нет']) ?>
              
            <? if(!empty($model->url_image)) echo '<img src="'.Yii::$app->params['productImagePath'].$model->url_image.'" width="100px">'; ?>

            <?= $form->field($model, 'imageFile')->fileInput() ?>
 
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6">
            <?php
                foreach ($values as $idKey => $itemValue){
                   echo $form->field($itemValue, '['.$itemValue->productAttribute->id.']'.'value')->label($itemValue->productAttribute->name)->textInput();
                }
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
