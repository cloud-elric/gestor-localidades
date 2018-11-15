<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatEstados;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidadesArchivadasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'panel-search-form',
    ],
    //'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="row">

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

    <?php // $form->field($model, 'id_localidad') ?>

    <?php // $form->field($model, 'id_estado') ?>

    <?php // $form->field($model, 'id_usuario') ?>

    <?php // $form->field($model, 'id_moneda') ?>

    <?php // $form->field($model, 'cms') ?>

    <?php // echo $form->field($model, 'txt_token') ?>

    <?php // echo $form->field($model, 'txt_nombre') ?>

    <?php // echo $form->field($model, 'txt_arrendador') ?>

    <?php // echo $form->field($model, 'txt_beneficiario') ?>

    <?php // echo $form->field($model, 'txt_calle') ?>

    <?php // echo $form->field($model, 'txt_colonia') ?>

    <?php // echo $form->field($model, 'txt_municipio') ?>

    <?php // echo $form->field($model, 'txt_cp') ?>

    <?php // echo $form->field($model, 'txt_estatus') ?>

    <?php // echo $form->field($model, 'txt_antecedentes') ?>

    <?php // echo $form->field($model, 'num_renta_actual') ?>

    <?php // echo $form->field($model, 'num_incremento_autorizado') ?>

    <?php // echo $form->field($model, 'num_pretencion_renta') ?>

    <?php // echo $form->field($model, 'num_incremento_cliente') ?>

    <?php // echo $form->field($model, 'num_pretencion_renta_cliente') ?>

    <?php // echo $form->field($model, 'fch_vencimiento_contratro') ?>

    <?php // echo $form->field($model, 'fch_creacion') ?>

    <?php // echo $form->field($model, 'fch_asignacion') ?>

    <?php // echo $form->field($model, 'b_problemas_acceso') ?>

    <?php // echo $form->field($model, 'b_archivada') ?>

    <?php // echo $form->field($model, 'b_status_localidad') ?>

    <div class="col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <?= Html::submitButton('<i class="icon wb-search" aria-hidden="true"></i>', ['class' => 'btn btn-search']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

