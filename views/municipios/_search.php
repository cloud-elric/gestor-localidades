<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatMunicipiosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cat-municipios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_municipio') ?>

    <?= $form->field($model, 'id_estado') ?>

    <?= $form->field($model, 'id_area') ?>

    <?= $form->field($model, 'txt_nombre') ?>

    <?= $form->field($model, 'txt_descripcion') ?>

    <?php // echo $form->field($model, 'num_latitud') ?>

    <?php // echo $form->field($model, 'num_longitud') ?>

    <?php // echo $form->field($model, 'b_habilitado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
