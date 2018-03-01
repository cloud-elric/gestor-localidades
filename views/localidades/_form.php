<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatEstados;
use app\modules\ModUsuarios\models\EntUsuarios;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if(true/*$flag$model->isNewRecord*/){ ?>
    <div class="ent-localidades-form">

        <?php $form = ActiveForm::begin(['id' => 'form12',
        'options' => [
            "class" => "panel-search-form"
        ]]); ?>
        
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4">
                
                <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'txt_arrendador')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'txt_beneficiario')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'txt_antecedentes')->textarea(['rows' => 6]) ?>

                <?= $form->field($estatus, 'txt_estatus')->textarea(['rows' => 6]) ?>

            </div>
            
            <div class="col-sm-12 col-md-6 col-lg-4">
                
                <?= $form->field($model, 'id_estado')->dropDownList(ArrayHelper::map(CatEstados::find()->orderBy('txt_nombre')->asArray()->all(), 'id_estado', 'txt_nombre'),['prompt' => 'Seleccionar estado']) ?>

                <?= $form->field($model, 'txt_calle')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'txt_colonia')->textInput(['maxlength' => true]) ?>
                
                <?= $form->field($model, 'txt_municipio')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'txt_cp')->textInput(['maxlength' => true]) ?>

            </div>
            
            <div class="col-sm-12 col-md-12 col-lg-4">
                

                <?= $form->field($model, 'num_renta_actual')->textInput() ?>

                <?= $form->field($model, 'num_incremento_autorizado')->textInput() ?>

                <?= $form->field($model, 'fch_vencimiento_contratro')->widget(DatePicker::classname(), [
                    //'options' => ['placeholder' => 'Enter birth date ...'],
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'mm-dd-yyyy'
                    ]
                ]);?>

                <?php // $form->field($model, 'fch_creacion')->textInput() ?>

                <?= $form->field($model, 'fch_asignacion')->widget(DatePicker::classname(), [
                    //'options' => ['placeholder' => 'Enter birth date ...'],
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'mm-dd-yyyy'
                    ]
                ]);?>

                <?= $form->field($model, 'b_problemas_acceso')->dropDownList(['1'=>'Sí', '0'=>"No"]) ?>

                <?= Html::submitButton('<i class="icon wb-plus"></i> Guardar', ['class' => 'btn btn-success btn-form-save']) ?>
                
            </div>
        </div>
    
        <?php ActiveForm::end(); ?>

    </div>

    <?php if($historial){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3>Historial de estatus</h3>
                    </div>
                    <?php if($historial){ ?>
                        <div class="panel-body">
                            <?php foreach($historial as $his){ ?>
                                <p><?= $his->txt_estatus ?></p>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>
                        <div class="panel-body">
                            <p>No hay historial de estatus</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

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
<?php } ?>

