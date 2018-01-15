<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'txt_auth_item')->dropDownList(ArrayHelper::map(AuthItem::find()->orderBy('name')->asArray()->all(), 'name', 'name'), ['prompt' => 'Seleccionar item']) ?>

    <?php //echo $form->field($model, 'txt_token')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'txt_imagen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_apellido_materno')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'txt_auth_key')->textInput(['maxlength' => true]) ?>

    <?php
    if($model->isNewRecord){
        echo $form->field($model, 'txt_password_hash')->textInput(['maxlength' => true]);
    }    
    ?>

    <?php //echo $form->field($model, 'txt_password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_email')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'fch_creacion')->textInput() ?>

    <?php //echo $form->field($model, 'fch_actualizacion')->textInput() ?>

    <?php //echo $form->field($model, 'id_status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>