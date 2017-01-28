<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form ActiveForm */
?>
<div class="edit">

    <?php


    $form = ActiveForm::begin();

    ?>

        <?= $form->field($model, 'login', []) ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'name') ?>
        <?=$form->field($model, 'active')->checkbox(['id' => 'active', 'checked' => true])->error(false); ?>
        <?= $form->field($model, 'password') ?>
        <?= $form->field($model, 'password_repeat') ?>


        <h3>Управление ролями пользователя <?= $model->getUserName(); ?></h3>

        <?= Html::checkboxList('roles', $user_permission, $roles, ['separator' => '<br>']); ?>

    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- edit -->
