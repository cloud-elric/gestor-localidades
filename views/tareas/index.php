<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TareasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wrk Tareas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrk-tareas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Wrk Tareas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_tarea',
            'id_usuario',
            'id_tarea_padre',
            'id_localidad',
            'txt_nombre',
            //'txt_descripcion:ntext',
            //'fch_creacion',
            //'fch_due_date',
            //'b_completa',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
