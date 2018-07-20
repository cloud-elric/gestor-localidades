<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->registerJsFile(
    '@web/webAssets/js/localidades/index.js',
    ['depends' => [AppAsset::className()]]
);

$this->title = 'Actualizar '.$model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_localidad, 'url' => ['view', 'id' => $model->id_localidad]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ent-localidades-update">

    

    <?= $this->render('_form', [
        'model' => $model,
        'estatus' => $estatus,
        'flag' => $flag,
        'historial' => $historial        
    ]) ?>

</div>
