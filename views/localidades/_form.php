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

<div class="ent-localidades-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_estado')->dropDownList(ArrayHelper::map(CatEstados::find()->orderBy('txt_nombre')->asArray()->all(), 'id_estado', 'txt_nombre'),['prompt' => 'Seleccionar estado']) ?>

    <?php // $form->field($model, 'id_usuario')->dropDownList(ArrayHelper::map(EntUsuarios::find()->orderBy('txt_username')->asArray()->all(), 'id_usuario', 'txt_username'),['prompt' => 'Seleccionar usuario']) ?>

    <?php // $form->field($model, 'txt_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_arrendador')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_beneficiario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_colonia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_municipio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_cp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_estatus')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'txt_antecedentes')->textarea(['rows' => 6]) ?>

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

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
