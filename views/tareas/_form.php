<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WrkTareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wrk-tareas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_usuario')->hiddenInput(['value' => $idUser])->label(false) ?>

    <?= $form->field($model, 'id_tarea_padre')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_localidad')->hiddenInput(['value' => $idLoc])->label(false) ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'txt_descripcion')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'fch_creacion')->textInput() ?>

    <?php // $form->field($model, 'fch_due_date')->textInput() ?>

    <?php // $form->field($model, 'b_completa')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
