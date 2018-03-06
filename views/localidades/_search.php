<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatEstados;

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

    <?php // $form->field($model, 'id_localidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true, "class"=>"panel-search-form-select", "placeholder"=>"Buscar por nombre"])->label(false) ?>    

    <?= $form->field($model, 'id_estado')->dropDownList(ArrayHelper::map(CatEstados::find()->orderBy('txt_nombre')->asArray()->all(), 'id_estado', 'txt_nombre'),['prompt' => 'Seleccionar estado'])->label(false) ?>

    <?php // $form->field($model, 'id_usuario')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'txt_token')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'txt_arrendador')->textInput(['maxlength' => true, "class"=>"panel-search-form-select", "placeholder"=>"Cliente"])->label(false) ?>

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

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>

 