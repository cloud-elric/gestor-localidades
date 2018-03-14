<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CatCodigosPostales */

$this->title = 'Create Cat Codigos Postales';
$this->params['breadcrumbs'][] = ['label' => 'Cat Codigos Postales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-codigos-postales-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
