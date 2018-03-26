<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = 'Crear Localidades';
$this->params['breadcrumbs'][] = ['label' => 'Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-create">

    <?= $this->render('_form', [
        'model' => $model,
        'estatus' => $estatus,
        'historial' => $historial,
        //'monedas' => $monedas,
        'flag' => $flag  
    ]) ?>

</div>
