<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatMunicipios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cat-municipios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num_latitud')->textInput() ?>

    <?= $form->field($model, 'num_longitud')->textInput() ?>

    <?= $form->field($model, 'b_habilitado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
