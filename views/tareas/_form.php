<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

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

    <?= $form->field($model, 'id_tipo')->hiddenInput()->label(false) ?>

    <p>
        <input name="group1" type="radio" id="test-archivo-<?=$idUser?>" value="archivo" onclick="asignarTipo($(this));"/> 
        <label for="test-archivo-<?=$idUser?>">Archivo</label>
    </p>
    <p>
        <input name="group1" type="radio" id="test-texto-<?=$idUser?>" value="texto" onclick="asignarTipo($(this));" /> 
        <label for="test-texto-<?=$idUser?>">Texto</label>
    </p>

    <?php // $form->field($model, 'fch_creacion')->textInput() ?>

    <?php // $form->field($model, 'fch_due_date')->textInput() ?>

    <?php // $form->field($model, 'b_completa')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

function asignarTipo(input){
    if(input.val() == 'archivo'){
        $('#wrktareas-id_tipo').val('1');
    }
    if(input.val() == 'texto'){
        $('#wrktareas-id_tipo').val('2');
    }
}

", View::POS_END );
?>