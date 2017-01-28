<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login', []) ?>
    <?= $form->field($model, 'email') ?>
    <?= $form->field($model, 'name') ?>
    <?=$form->field($model, 'active')->checkbox(['id' => 'active', 'checked' => true])->error(false); ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>


    <h3>Управление ролями пользователя <?= $model->getUserName(); ?></h3>

    <?= Html::checkboxList('roles', $user_permission, $roles, ['separator' => '<br>']); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
