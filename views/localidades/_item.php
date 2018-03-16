<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Dropbox;
use app\models\WrkUsuariosTareas;
use app\models\ConstantesWeb;

/* @var $this yii\web\View */
/* @var $model app\models\WrkTareas */
/* @var $form yii\widgets\ActiveForm */

$localidad = $model->localidad;
$dropbox = Dropbox::listarFolder($localidad->txt_nombre);
$decodeDropbox = json_decode(trim($dropbox), TRUE);
$existeArchivo = false;
foreach($decodeDropbox['entries'] as $retorno){
    if($retorno['name'] == $model->txt_nombre){
        $existeArchivo = true;
    } 
}

$user = Yii::$app->user->identity;
$tarea = $model->id_tarea;
$rel = WrkUsuariosTareas::find()->where(['id_usuario'=>$user->id_usuario])->andWhere(['id_tarea'=>$tarea])->one();
?>

<div class="wrk-tareas-form">

    <?php $form = ActiveForm::begin([
        'action' => ['tareas/update', 'id'=>$model->id_tarea],
        'method' => 'POST'
    ]); ?>

    <?= $form->field($model, 'id_usuario')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_tipo')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_tarea_padre')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id_localidad')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true, 'disabled'=>true]) ?>

    <?php 
    if($rel || $user->txt_auth_item == ConstantesWeb::CLIENTE){ 
        if($model->id_tipo == 1){
    ?>
            <?= $form->field($model, 'file')->fileInput(['data-id'=>$tarea]) ?>
    <?php 
        }
        if($model->id_tipo == 2){
    ?>
            <?= $form->field($model, 'txt_tarea')->textarea(['rows' => 6, 'data-id'=>$tarea]) ?>
    <?php
        }
    }
    ?>

    <?php // $form->field($model, 'fch_creacion')->textInput() ?>

    <?php // $form->field($model, 'fch_due_date')->textInput() ?>

    <?php // $form->field($model, 'b_completa')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['id' => 'btnGuardarArchivo-'.$tarea, 'class' => 'btn btn-success', 'style' => 'display:none' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if($existeArchivo){?>
        <?= Html::a('Descargar', [
            'tareas/descargar', 'id' => $model->id_tarea,

        ], ['target' => '_blank']);?>
    <?php } ?>

</div>
