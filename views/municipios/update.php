<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CatMunicipios */

$this->title = 'Update Cat Municipios: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Cat Municipios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_municipio, 'url' => ['view', 'id' => $model->id_municipio]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cat-municipios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
