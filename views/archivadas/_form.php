<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidadesArchivadas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-localidades-archivadas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_moneda')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cms')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_token')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'num_pretencion_renta')->textInput() ?>

    <?= $form->field($model, 'num_incremento_cliente')->textInput() ?>

    <?= $form->field($model, 'num_pretencion_renta_cliente')->textInput() ?>

    <?= $form->field($model, 'fch_vencimiento_contratro')->textInput() ?>

    <?= $form->field($model, 'fch_creacion')->textInput() ?>

    <?= $form->field($model, 'fch_asignacion')->textInput() ?>

    <?= $form->field($model, 'b_problemas_acceso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'b_archivada')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'b_status_localidad')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
