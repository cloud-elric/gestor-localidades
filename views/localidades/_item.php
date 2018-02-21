<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WrkTareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wrk-tareas-form">

    <?php $form = ActiveForm::begin([
        'action' => ['tareas/update', 'id'=>$model->id_tarea],
        'method' => 'POST'
    ]); ?>

    <?= $form->field($model, 'id_usuario')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_tarea_padre')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_localidad')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?php // $form->field($model, 'txt_descripcion')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'fch_creacion')->textInput() ?>

    <?php // $form->field($model, 'fch_due_date')->textInput() ?>

    <?php // $form->field($model, 'b_completa')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
