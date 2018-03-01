<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WrkTareas */

$this->title = 'Crear Tarea';
$this->params['breadcrumbs'][] = ['label' => 'Wrk Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrk-tareas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'idLoc' => $idLoc,
        'idUser' => $idUser
    ]) ?>

</div>
