<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatEstados;
use app\models\ConstantesWeb;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidadesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'panel-search-form',
    ],
    /*'action' => ['index'],
    'class' => 'panel-search-form',*/
    'method' => 'get',
]); ?>

<div class="row">

    <?php // $form->field($model, 'id_localidad')->textInput(['maxlength' => true]) ?>

    <div class="col-sm-12 col-md-3 col-lg-3">
        <?php // $form->field($model, 'txt_nombre')->textInput(['maxlength' => true, "class"=>"panel-search-form-input", "placeholder"=>"Buscar por nombre"])->label(false) ?>    
        <?= $form->field($model, 'txt_arrendador')->textInput(['maxlength' => true, "class"=>"panel-search-form-input", "placeholder"=>"Buscar por Arrendador"])->label(false) ?>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3">
        <?php // $form->field($model, 'id_estado')->dropDownList(ArrayHelper::map(CatEstados::find()->orderBy('txt_nombre')->asArray()->all(), 'id_estado', 'txt_nombre'),['prompt' => 'Seleccionar estado', "class"=>"panel-search-form-select"])->label(false) ?>
        <?= $form->field($model, 'cms')->textInput(['maxlength' => true, "class"=>"panel-search-form-input", "placeholder"=>"Buscar localidad"])->label(false) ?>    
    </div>

    <?php // $form->field($model, 'id_usuario')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'txt_token')->textInput(['maxlength' => true]) ?>

    <div class="col-sm-12 col-md-4 col-lg-4">
        <?php // $form->field($model, 'txt_arrendador')->textInput(['maxlength' => true, "class"=>"panel-search-form-input", "placeholder"=>"Arrendador"])->label(false) ?>
        <?= $form->field($model, 'b_status_localidad')->dropDownList(['1'=>'Regularización', '2'=>'Renovación'], ["prompt"=>"Filtrar por Regularización / Renovación"])->label(false) ?>    
    </div>
    
    <?php // echo $form->field($model, 'txt_beneficiario') ?>

    <?php // echo $form->field($model, 'txt_calle') ?>

    <?php // echo $form->field($model, 'txt_colonia') ?>

    <?php // echo $form->field($model, 'txt_municipio') ?>

    <?php // echo $form->field($model, 'txt_cp') ?>

    <?php // echo $form->field($model, 'txt_estatus')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'txt_antecedentes') ?>

    <?php // echo $form->field($model, 'num_renta_actual') ?>

    <?php // echo $form->field($model, 'num_incremento_autorizado') ?>

    <?php // echo $form->field($model, 'fch_vencimiento_contratro') ?>

    <?php // echo $form->field($model, 'fch_creacion') ?>

    <?php // echo $form->field($model, 'fch_asignacion') ?>

    <?php // echo $form->field($model, 'b_problemas_acceso') ?>

    <?php // echo $form->field($model, 'b_archivada') ?>

    <div class="col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <?= Html::submitButton('<i class="icon wb-search" aria-hidden="true"></i>', ['class' => 'btn btn-search']) ?>
            <!-- <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?> -->
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 text-right">
                
        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO || Yii::$app->user->identity->txt_auth_item == ConstantesWeb::SUPER_ADMIN){ ?>
        <?php } ?>

    </div>

    <div class="col-sm-12 mt-20 text-right">
        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO || Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ASISTENTE){ ?>
            <?= Html::a('<i class="icon ion-md-trending-up" aria-hidden="true"></i> Agregar', ['create'], ['class' => 'btn btn-add no-pjax']) ?>
            <?= Html::a('<i class="icon ion-md-download" aria-hidden="true"></i> Exportar', ['exportar-localidades'], ['class' => 'btn btn-add no-pjax', 'target'=>'_blank']) ?>
            <?php # Html::a('<i class="icon ion-md-archive" aria-hidden="true"></i> Archivar', Url::base().'/archivadas/index', ['class' => 'btn btn-add no-pjax']) ?>
        <?php } ?>
    </div>

 </div>

 <?php ActiveForm::end(); ?>