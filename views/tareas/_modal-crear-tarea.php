<?php
use yii\bootstrap\Modal;
use app\models\WrkTareas;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\models\ConstantesWeb;

$tarea = new WrkTareas();

Modal::begin([
    'id'=>"modal-crear-tarea",
    'header' => '<h2>Agregar tarea</h2>',
]);

 $form = ActiveForm::begin(["id"=>"form-guardar-tarea"]); ?>

    <?= $form->field($tarea, 'id_localidad')->hiddenInput()->label(false) ?>

    <?= $form->field($tarea, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?=$form->field($tarea, 'id_tipo')->inline()->radioList([ConstantesWeb::TAREA_ARCHIVO=>"Archivo", ConstantesWeb::TAREA_ABIERTO=>"Texto"]);?>


    <div class="form-group">
        <?= Html::submitButton('<span class="ladda-label">Guardar</span>', ['class' => 'btn btn-success ladda-button', "id"=>"js-btn-guardar-tarea", "data-style"=>"zoom-in"]) ?>
    </div>

    <?php ActiveForm::end(); 

Modal::end();