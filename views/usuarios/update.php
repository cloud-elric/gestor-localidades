<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */

$this->title = 'Actualizar Usuarios: ' . $model->txt_username;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->txt_username, 'url' => ['view', 'id' => $model->id_usuario]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ent-usuarios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>