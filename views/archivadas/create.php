<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidadesArchivadas */

$this->title = 'Create Ent Localidades Archivadas';
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades Archivadas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-archivadas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
