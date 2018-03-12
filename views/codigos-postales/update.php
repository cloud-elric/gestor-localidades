<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CatCodigosPostales */

$this->title = 'Update Cat Codigos Postales: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Cat Codigos Postales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->txt_codigo_postal, 'url' => ['view', 'id' => $model->txt_codigo_postal]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cat-codigos-postales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
