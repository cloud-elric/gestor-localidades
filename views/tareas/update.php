<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WrkTareas */

$this->title = 'Update Wrk Tareas: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Wrk Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_tarea, 'url' => ['view', 'id' => $model->id_tarea]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wrk-tareas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
