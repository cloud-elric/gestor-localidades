<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = 'Actualizar '.$model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_localidad, 'url' => ['view', 'id' => $model->id_localidad]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ent-localidades-update">

    

    <?= $this->render('_form', [
        'model' => $model,
        'estatus' => $estatus,
        'historial' => $historial        
    ]) ?>

</div>
