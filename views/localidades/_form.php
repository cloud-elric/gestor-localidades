<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatEstados;
use app\modules\ModUsuarios\models\EntUsuarios;
use kartik\date\DatePicker;
use yii\web\View;
use app\models\CatPorcentajeRentaAbogados;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\depdrop\DepDrop;
use app\models\CatColonias;
use app\models\CatTiposMonedas;
use app\models\CatRegularizacionRenovacion;
use app\assets\AppAsset;
use app\models\ConstantesWeb;
use app\models\WrkUsuarioUsuarios;
use app\models\CatEstatusTracker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */
/* @var $form yii\widgets\ActiveForm */

$estado = $model->estado;
$idUser = Yii::$app->user->identity->id_usuario;

if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ASISTENTE){
    $rel = WrkUsuarioUsuarios::find()->where(['id_usuario_hijo'=>$idUser])->one();
    $porcentajeAbogado = CatPorcentajeRentaAbogados::find()->where(['id_usuario'=>$rel->id_usuario_padre])->one();
}else{
    $porcentajeAbogado = CatPorcentajeRentaAbogados::find()->where(['id_usuario'=>$idUser])->one();
}

$this->registerJsFile(
    '@web/webAssets/plugins/moment/moment.js',
    ['depends' => [AppAsset::className()]]
);

?>

<?php if($flag/*$model->isNewRecord*/){ ?>
    <div class="ent-localidades-form">
    <?php if (Yii::$app->session->hasFlash('error')): ?>
			<div class="alert alert-danger alert-dismissable dark">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			<h4><i class="icon fa fa-warning"></i>Espera</h4>
			<?= Yii::$app->session->getFlash('error') ?>
			</div>
		<?php endif; ?>


        <?php $form = ActiveForm::begin([
            'id' => 'form-crear-localidad'
        ]); ?>

        <div class="panel">
            <div class="panel-heading">
                 <h2 class="panel-title">Datos generales</h2>   
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'cms')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'txt_arrendador')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'txt_beneficiario')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'txt_contacto')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?php // $form->field($model, 'id_estatus_tracker')->dropDownList(ArrayHelper::map(CatEstatusTracker::find()->where(['b_habilitado'=>1])->orderBy('txt_estatus_tracker')->all(), 'id_estatus_tracker', 'txt_estatus_tracker'), ['prompt'=>'Seleccionar estatus']); ?>
                        <?= $form->field($model, 'txt_tag_estatus_tracker')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(CatEstatusTracker::find()->where(['b_habilitado'=>1])->orderBy('txt_estatus_tracker')->all(), 'txt_estatus_tracker', 'txt_estatus_tracker'),
                            'options' => ['multiple' => true],
                            'value' => $model->txt_tag_estatus_tracker,
                            'pluginOptions' => [
                                'tags' => false,
                                'tokenSeparators' => ['|'],
                                'maximumInputLength' => 10
                            ],
                        ])->label('Seleccionar estatus'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <?= $form->field($model, 'txt_antecedentes')->textarea(['rows' => 6]) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <?= $form->field($estatus, 'txt_estatus')->textarea(['rows' => 6]) ?>
                    </div>
                </div>
                
               
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h2 class="panel-title">Datos de ubicación</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="list-unstyled list-inline">
                            <li class="list-inline-item">
                                <div class=" radio-custom radio-warning">
                                    <!-- <input id="inputRadiosChecked" name="inputRadios" checked="" type="radio"> -->
                                    <input type="radio" name="tipo_ubicacion" id="js-automatico" value="0" <?= !$model->tipoUbicacion ? 'checked':'' ?>>
                                    <label for="js-automatico">Automático</label>
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <div class="list-inline-item radio-custom radio-warning">
                                    <!-- <input id="inputRadiosChecked" name="inputRadios" type="radio"> -->
                                    <input type="radio" name="tipo_ubicacion" id="js-manual" value="1" <?= $model->tipoUbicacion ? 'checked':'' ?>>
                                    <label for="js-manual">Manual</label>
                                </div>
                            </li>

                        </div>

                        
                        
                    </div>

                    <div class="col-md-12" id="js-div-automatico">
                        <div class="row">                        
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?php 
                                require(__DIR__ . '/../components/select2.php');
                                $url = Url::to(['codigos-postales/buscar-codigo']);

                                echo $form->field($model, 'txt_cp')->widget(Select2::classname(), [
                                    //'initValueText' => empty($model->id_localidad) ? '' : $estado->txt_nombre,
                                    'options' => ['placeholder' => 'Seleccionar código postal'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'minimumInputLength' => 3,
                                        'ajax' => [
                                            'url' => $url,
                                            'dataType' => 'json',
                                            'delay' => 250,
                                            'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                                            'processResults' => new JsExpression($resultsJs),
                                            'cache' => true
                                        ],
                                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                        'templateResult' => new JsExpression('function(equipo) { return equipo.txt_nombre; }'),
                                        'templateSelection' => new JsExpression('function (equipo) { 
                                            if(equipo.txt_nombre){
                                                return equipo.txt_nombre;
                                            }else{
                                                return "'.$model->txt_cp.'"
                                            }
                                        }'),
                                    ],
                                ]); 
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <input id="texto_colonia" type="hidden" name="colonia" value="<?= $model->txt_colonia ?>">
                                <?php
                                echo $form->field($model, 'txt_colonia')->widget(DepDrop::classname(), [
                                    'data'=> ArrayHelper::map(CatColonias::find()->where(['txt_codigo_postal'=>$model->txt_cp])->all(), 'id_colonia', 'txt_nombre'),
                                    'options' => ['placeholder' => 'Seleccionar ...'],
                                    'type' => DepDrop::TYPE_SELECT2,
                                    'select2Options'=>[
                                        'pluginOptions'=>[
                                            
                                            'allowClear'=>true,
                                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            'templateResult' => new JsExpression('function(colonia) {return colonia.text; }'),
                                            'templateSelection' => new JsExpression('function (colonia) { return colonia.text; }'),
                                        ],
                                        ],
                                    'pluginOptions'=>[
                                        'depends'=>['entlocalidades-txt_cp'],
                                        'url' => Url::to(['/codigos-postales/get-colonias-by-codigo-postal?code='.$model->txt_cp]),
                                        'loadingText' => 'Cargando colonias ...',
                                    ]
                                ]);
                                ?>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?=Html::label("Delegación/Municipio", "txt_municipio", ['class'=>'control-label'])?>
                                <?=Html::textInput("txt_municipio", $model->txt_municipio, ['class'=>'form-control','disabled'=>'disabled', 'id'=>'txt_municipio' ])?>
                                <?= $form->field($model, 'txt_municipio')->hiddenInput(['maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?php
                                $estado = $model->estado;
                                if($estado){
                                    $estado = $estado->txt_nombre;
                                }
                                ?>
                                <?=Html::label("Estado", "txt_estado", ['class'=>'control-label'])?>
                                <?=Html::textInput("txt_estado", $estado, ['class'=>'form-control','disabled'=>'disabled', 'id'=>'txt_estado' ])?>
                                <?= $form->field($model, 'id_estado')->hiddenInput()->label(false) ?>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?= $form->field($model, 'txt_calle')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id='js-div-manual' style='display:none'>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?= $form->field($model, 'textoCP')->textInput(['maxlength' => true])->label() ?>
                            </div>

                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?= $form->field($model, 'textoColonia')->textInput(['maxlength' => true])->label() ?>
                            </div>
                                
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?= $form->field($model, 'textoMun')->textInput(['maxlength' => true])->label() ?>
                            </div>
                                
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?= $form->field($model, 'textoEstado')->textInput(['maxlength' => true])->label() ?>
                            </div>
                                
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <?= $form->field($model, 'textoCalle')->textInput(['maxlength' => true])->label() ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h2 class="panel-title">Datos de contrato</h2>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'fch_vencimiento_contratro')->widget(DatePicker::classname(), [
                            //'options' => ['placeholder' => 'Enter birth date ...'],
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'fch_asignacion')->widget(DatePicker::classname(), [
                            //'options' => ['placeholder' => 'Enter birth date ...'],
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd-mm-yyyy'
                            ]
                        ]);?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'b_status_localidad')->radioList(ArrayHelper::map(CatRegularizacionRenovacion::find()->all(), 'id_catalogo','txt_nombre'), [
                            'item' => function($index, $label, $name, $checked, $value) {  
                            $checked = $checked?"checked":"";
                            $return = '<div class="list-inline-item"><input '.$checked.' type="radio" id="tipo_contrato_' . $value . '" name="' . $name . '" value="' . $value . '" onClick="statusLocalidad($(this));" disabled>';
                            $return .= '<label>' . ucwords($label) . '</label></div>';
                            return $return;
                        },
                        'class'=>'radio-custom radio-warning list-inline'])->label() ?>
                    </div>
                    
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'num_renta_actual')->textInput() ?>
                    </div>
                    
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'num_incremento_autorizado')->textInput(['value'=>$porcentajeAbogado->num_porcentaje]) ?>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'num_pretencion_renta')->textInput(['disabled'=>false]) ?>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'num_incremento_cliente')->textInput() ?>
                    </div>
                    
                    
                    
                    
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'num_pretencion_renta_cliente')->textInput() ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <?= $form->field($model, 'txt_frecuencia')->dropDownList(['mensual'=>'Mensual', 'trimestral'=>'Trimestral', 'semestral'=>'Semestral', 'anual'=>'Anual'], ['prompt'=>'Seleccionar opción']) ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'id_moneda')->radioList(ArrayHelper::map(CatTiposMonedas::find()->where(['b_habilitado'=>1])->all(), 'id_moneda', 'txt_siglas'), [
                            'item' => function($index, $label, $name, $checked, $value) {  
                                $checked = $checked?"checked":"";
                                $return = '<div class="list-inline-item"><input type="radio" '.$checked.' name="' . $name . '" value="' . $value . '" >';
                                $return .= '<label>' . ucwords($label) . '</label></div>';
                                return $return;
                            }
                        ,'class'=>'radio-custom radio-warning list-inline']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <?= $form->field($model, 'b_problemas_acceso')->dropDownList([ '0'=>"No", '1'=>'Sí']) ?>
                    </div>
                </div>
            </div>
        </div>

                

        <div class="row">
            <div class="col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4">
  
                <?= Html::submitButton('<i class="icon fa-save font-size-18" aria-hidden="true"></i> Guardar', ['id'=>'js-btn-guardar-loc', 'class' => 'btn btn-success btn-form-save ladda-button', "data-style"=>"zoom-in"]) ?>
                
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="ent-localidades-update-history">
    <?php if($historial){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-history">
                    <div class="panel-heading">
                        <h3>Historial de comentarios</h3>
                    </div>
                    <?php if($historial){ ?>
                        <div class="panel-body">
                            <?php foreach($historial as $his){ ?>
                                <p><?= $his->txt_estatus ?></p>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>
                        <div class="panel-body">
                            <p>No hay historial de comentarios</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
    
<?php }else{ ?>
    <?= Html::beginForm([
            'localidades/index',
            "class" => "panel-search-fo",
            'method' => 'post'
    ]); ?>
        <!-- <input type="text" class="panel-search-form-select" placeholder="Buscar por nombre"> -->
        <?= Html::activeInput('text', $model, 'txt_nombre', ["class"=>"panel-search-form-select", "placeholder"=>"Buscar por nombre"]) ?>

        <!-- <input type="text" class="panel-search-form-input ml-35" placeholder="Cliente"> -->
        <?= Html::activeInput('text', $model, 'txt_arrendador', ["class"=>"panel-search-form-input ml-35", "placeholder"=>"Cliente"]) ?>

        <!-- <input type="text" class="panel-search-form-input" placeholder="Estado"> -->      
        <?= Html::dropDownList('list', 'id_estado', ArrayHelper::map(CatEstados::find()->orderBy('txt_nombre')->asArray()->all(), 'id_estado', 'txt_nombre'),['prompt' => 'Seleccionar estado']) ?>

        <!-- <input type="text" class="panel-search-form-input" placeholder="Status">
        <input type="text" class="panel-search-form-input" placeholder="Tipo"> -->
        <?= Html::submitButton('<i class="icon wb-plus"></i> Buscar', ['class' => 'btn btn-success btn-form-save']) ?>
    <?= Html::endForm() ?>
<?php } 

$baseUrl = Yii::$app->urlManager->createAbsoluteUrl(['municipios/get-municipio-by-colonia?colonia=']);

$this->registerJs("

$(document).ready(function(){

    //$('#entlocalidades-b_status_localidad').val('1');

    $('#js-automatico').on('click', function(){
        //console.log('ddffdfdfdsfds');
        $('#js-div-automatico').css('display', 'block');
        $('#js-div-manual').css('display', 'none');
    });
    $('#js-manual').on('click', function(){
        //console.log('nbnbbn');
        $('#js-div-automatico').css('display', 'none');
        $('#js-div-manual').css('display', 'block');
    });
    if($('#js-manual').is(':checked')){
        //console.log('dddd');
        $('#js-div-automatico').css('display', 'none');
        $('#js-div-manual').css('display', 'block');
    };

    $('#entlocalidades-num_renta_actual').on('change', function(){
        if( $('#entlocalidades-b_status_localidad').val() == '1' ){
            rentaActual = $(this).val();
            porcentaje = $('#entlocalidades-num_incremento_autorizado').val();
            
            porce = porcentaje / 100;
            incremento = rentaActual * porce;
            total = parseFloat(rentaActual) + parseFloat(incremento);

            $('#entlocalidades-num_pretencion_renta').val(total.toFixed(2));
        }
        if($('#entlocalidades-num_incremento_cliente').val() > 1){
            $('#entlocalidades-num_incremento_cliente').change();
        }
    });

    $('#entlocalidades-num_incremento_autorizado').on('change', function(){
        if( $('#entlocalidades-b_status_localidad').val() == '1' ){
            porcentaje = $(this).val();
            rentaActual = $('#entlocalidades-num_renta_actual').val();
            
            porce = porcentaje / 100;
            incremento = rentaActual * porce;
            total = parseFloat(rentaActual) + parseFloat(incremento);

            if(porcentaje > 1){
                $('#entlocalidades-num_pretencion_renta').val(total.toFixed(2));
            }else{
                $('#entlocalidades-num_pretencion_renta').val(0);
            }
        }
    });

    $('.btn-form-save').on('click', function(){
        $('#entlocalidades-num_incremento_autorizado').removeAttr('disabled');
        $('#entlocalidades-num_pretencion_renta').removeAttr('disabled');
    });

    $('#entlocalidades-txt_colonia').on('change', function(){
        var id = $(this).val();
        console.log(id);
        if(id){
            buscarMunicipioByColonia($(this).val());
        }
    });

    $('#entlocalidades-num_incremento_cliente').on('change', function(){
        rentaActual = $('#entlocalidades-num_renta_actual').val();
        porcentaje = $(this).val();

        porce = porcentaje / 100;
        incremento = rentaActual * porce;
        total = parseFloat(rentaActual) + parseFloat(incremento);

        if(porcentaje > 1){
            $('#entlocalidades-num_pretencion_renta_cliente').val(total.toFixed(2));
        }else{
            $('#entlocalidades-num_pretencion_renta_cliente').val(0);
        }
    });

    $('#entlocalidades-num_pretencion_renta_cliente').on('change', function(){
        rentaActual = $('#entlocalidades-num_renta_actual').val();
        pretencion = $(this).val();

        num1 = parseInt(pretencion) - parseInt(rentaActual);
        num2 = num1 * 100;
        porcentaje = num2 / rentaActual;

        $('#entlocalidades-num_incremento_cliente').val(porcentaje);
    });

    $('#entlocalidades-num_pretencion_renta').on('change', function(){
        rentaActual = $('#entlocalidades-num_renta_actual').val();
        pretencion = $(this).val();

        num1 = parseInt(pretencion) - parseInt(rentaActual);
        num2 = num1 * 100;
        porcentaje = num2 / rentaActual;

        $('#entlocalidades-num_incremento_autorizado').val(porcentaje);
    });

    var regularizacion = $('#tipo_contrato_1');
    var renovacion = $('#tipo_contrato_2');

    if(regularizacion.attr('checked')){
        statusLocalidad(regularizacion);
    }
    if(renovacion.attr('checked')){
        statusLocalidad(renovacion);
    }

    $('#entlocalidades-fch_vencimiento_contratro').on('change', function(){
        
        var fechaActual = new Date();
        var fechaVencimiento = new Date(moment($(this).val(), 'DD-MM-YYYY'));
        var diferencia = fechaActual - fechaVencimiento;
        var dif = Math.floor((diferencia) / (1000*60*60*24));

        console.log(fechaActual);
        console.log(fechaVencimiento);
        console.log( Math.floor((diferencia) / (1000*60*60*24)) );

        if(dif == 0){
            regularizacion.prop('checked', false);
            renovacion.prop('checked', false);
        }else if(dif < 0){
            regularizacion.prop('checked', true);
            statusLocalidad(regularizacion);
            //console.log('Regularizacion');
        }else{
            renovacion.prop('checked', true);
            statusLocalidad(renovacion);           
            //console.log('Renovacion');            
        }
    });

    $('#form-crear-localidad').submit(function(){
        regularizacion.prop('disabled', false);
        renovacion.prop('disabled', false);

        return true;
    });
});

$(document).on({
    'keypress': function(e){
    
        if(String.fromCharCode(event.which)=='.'){
            var str = $(this).val();
            var n = str.indexOf('.');

            if(str.length==0 || n==0){
                $(this).val('0.');
                return false;
            }

            if(n>0){
                return false;
            }

        }else if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        
            return false;
        }
    }
}, '#entlocalidades-num_renta_actual, #entlocalidades-num_pretencion_renta, #entlocalidades-num_pretencion_renta_cliente');

$(document).on({
    'keypress': function(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }
}, '#entlocalidades-num_incremento_autorizado, #entlocalidades-num_incremento_cliente');

function statusLocalidad(input){
    if(input.val() == 2){
        // $('#entlocalidades-num_pretencion_renta').val('0');
        // $('.field-entlocalidades-num_incremento_autorizado').css('display', 'none');
        // $('.field-entlocalidades-num_pretencion_renta').css('display', 'none');
        // $('#entlocalidades-b_status_localidad').val('2');

        var padreIncrementoAutorizado = $('.field-entlocalidades-num_incremento_autorizado').parent();
        var padrePretencionRenta = $('.field-entlocalidades-num_pretencion_renta').parent();
        var padreIncrementoCliente = $('.field-entlocalidades-num_incremento_cliente').parent();
        var padrePretencionRentaCliente = $('.field-entlocalidades-num_pretencion_renta_cliente').parent();

        padreIncrementoAutorizado.css('display', 'block');
        padrePretencionRenta.css('display', 'block');
        padreIncrementoCliente.css('display', 'block');
        padrePretencionRentaCliente.css('display', 'block');
        $('#entlocalidades-b_status_localidad').val('1');

        rentaActual = $('#entlocalidades-num_renta_actual').val();
        porcentaje = $('#entlocalidades-num_incremento_autorizado').val();
        
        porce = porcentaje / 100;
        incremento = rentaActual * porce;
        total = parseInt(rentaActual) + parseInt(incremento);

        if(rentaActual > 1){
            $('#entlocalidades-num_pretencion_renta').val(total);
        }else{
            $('#entlocalidades-num_pretencion_renta').val(0);
        }
    }else{
        $('#entlocalidades-num_pretencion_renta').val('0');

        var padreIncrementoAutorizado = $('.field-entlocalidades-num_incremento_autorizado').parent();
        var padrePretencionRenta = $('.field-entlocalidades-num_pretencion_renta').parent();
        var padreIncrementoCliente = $('.field-entlocalidades-num_incremento_cliente').parent();
        var padrePretencionRentaCliente = $('.field-entlocalidades-num_pretencion_renta_cliente').parent();

        padreIncrementoAutorizado.css('display', 'none');
        padrePretencionRenta.css('display', 'none');
        padreIncrementoCliente.css('display', 'none');
        padrePretencionRentaCliente.css('display', 'none');

        $('#entlocalidades-b_status_localidad').val('2');

        // $('.field-entlocalidades-num_incremento_autorizado').css('display', 'block');
        // $('.field-entlocalidades-num_pretencion_renta').css('display', 'block');
        // $('#entlocalidades-b_status_localidad').val('1');

        // rentaActual = $('#entlocalidades-num_renta_actual').val();
        // porcentaje = $('#entlocalidades-num_incremento_autorizado').val();
        
        // porce = porcentaje / 100;
        // incremento = rentaActual * porce;
        // total = parseInt(rentaActual) + parseInt(incremento);

        // if(rentaActual > 1){
        //     $('#entlocalidades-num_pretencion_renta').val(total);
        // }else{
        //     $('#entlocalidades-num_pretencion_renta').val(0);
        // }
    }
}

function buscarMunicipioByColonia(colonia){
    $.ajax({
        url: '".$baseUrl."'+colonia,
        success:function(resp){
            console.log(resp);
            $('#entlocalidades-txt_municipio').val(resp.municipio.txt_nombre);
            $('#txt_municipio').val(resp.municipio.txt_nombre);
            $('#entlocalidades-id_estado').val(resp.estado.id_estado);
            $('#txt_estado').val(resp.estado.txt_nombre);
        }
    });
}

", View::POS_END );
?>

