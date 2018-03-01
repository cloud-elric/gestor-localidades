<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TareasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wrk-tareas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_tarea') ?>

    <?= $form->field($model, 'id_usuario') ?>

    <?= $form->field($model, 'id_tarea_padre') ?>

    <?= $form->field($model, 'id_localidad') ?>

    <?= $form->field($model, 'txt_nombre') ?>

    <?php // echo $form->field($model, 'txt_descripcion') ?>

    <?php // echo $form->field($model, 'fch_creacion') ?>

    <?php // echo $form->field($model, 'fch_due_date') ?>

    <?php // echo $form->field($model, 'b_completa') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
