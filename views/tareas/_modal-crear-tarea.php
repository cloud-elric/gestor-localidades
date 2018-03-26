<?php
use yii\bootstrap\Modal;
use app\models\WrkTareas;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\models\ConstantesWeb;

$tarea = new WrkTareas();

Modal::begin([
    'id'=>"modal-crear-tarea",
    'options'=>[
        'class'=>'modal modal-tarea'
    ],
    'header' => '<h2>Agregar tarea</h2>',
]);

 $form = ActiveForm::begin(["id"=>"form-guardar-tarea"]); ?>

    <?= $form->field($tarea, 'id_localidad')->hiddenInput()->label(false) ?>

    <?= $form->field($tarea, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?=$form->field($tarea, 'id_tipo')->inline()->radioList([ConstantesWeb::TAREA_ARCHIVO=>"Archivo", ConstantesWeb::TAREA_ABIERTO=>"Texto"]);?>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12"><label>Tipo de tarea</label></div>
            <div class="col-sm-6 col-md-3">
                <div class="radio-custom radio-warning">
                    <input type="radio" name="inputRadioWarning" checked="">
                    <label>Archivo</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="radio-custom radio-warning">
                    <input type="radio" name="inputRadioWarning" checked="">
                    <label>Texto</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('<span class="ladda-label">Guardar</span>', ['class' => 'btn btn-primary ladda-button', "id"=>"js-btn-guardar-tarea", "data-style"=>"zoom-in"]) ?>
    </div>

    <?php ActiveForm::end(); 

Modal::end();