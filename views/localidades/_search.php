<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidadesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-localidades-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_localidad') ?>

    <?= $form->field($model, 'id_estado') ?>

    <?= $form->field($model, 'id_usuario') ?>

    <?= $form->field($model, 'txt_token') ?>

    <?= $form->field($model, 'txt_nombre') ?>

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

</div>
