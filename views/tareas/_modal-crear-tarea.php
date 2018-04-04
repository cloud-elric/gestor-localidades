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

    <?=$form->field($tarea, 'id_tipo')->radioList([ConstantesWeb::TAREA_ARCHIVO=>"Archivo", ConstantesWeb::TAREA_ABIERTO=>"Texto"],[
                            'item' => function($index, $label, $name, $checked, $value) {  
                                $checked = $checked?"checked":"";
                                $return = ' <div class="col-sm-6 col-md-3"><div class="radio-custom radio-warning">
                                    <input type="radio" '.$checked.' name="' . $name . '" value="' . $value . '" id="'.$name.$index.'" >';
                                $return .= '<label  for="'.$name.$index.'">' . ucwords($label) . '</label></div></div>';
                                return $return;
                            }, "class"=>"row"]);?>


    <div class="form-group">
        <?= Html::submitButton('<span class="ladda-label"><i class="icon fa-save font-size-16" aria-hidden="true"></i> Guardar</span>', ['class' => 'btn btn-primary ladda-button', "id"=>"js-btn-guardar-tarea", "data-style"=>"zoom-in"]) ?>
    </div>

    <?php ActiveForm::end(); 

Modal::end();