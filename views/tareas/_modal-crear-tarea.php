<?php
use yii\bootstrap\Modal;
use app\models\WrkTareas;

$tarea = new WrkTareas();

Modal::begin([
    'header' => '<h2>Agregar tarea</h2>',
]);

echo $this->render("//tareas/_form");

Modal::end();