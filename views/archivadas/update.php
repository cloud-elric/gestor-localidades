<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidadesArchivadas */

$this->title = 'Update Ent Localidades Archivadas: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades Archivadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_localidad, 'url' => ['view', 'id' => $model->id_localidad]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ent-localidades-archivadas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
