<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WrkTareas */

$this->title = $model->id_tarea;
$this->params['breadcrumbs'][] = ['label' => 'Wrk Tareas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrk-tareas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_tarea], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_tarea], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_tarea',
            'id_usuario',
            'id_tarea_padre',
            'id_localidad',
            'txt_nombre',
            'txt_descripcion:ntext',
            'fch_creacion',
            'fch_due_date',
            'b_completa',
        ],
    ]) ?>

</div>
