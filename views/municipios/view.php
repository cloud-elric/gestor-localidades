<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CatMunicipios */

$this->title = $model->id_municipio;
$this->params['breadcrumbs'][] = ['label' => 'Cat Municipios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-municipios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_municipio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_municipio], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_municipio',
            'id_estado',
            'id_area',
            'txt_nombre',
            'txt_descripcion',
            'num_latitud',
            'num_longitud',
            'b_habilitado:boolean',
        ],
    ]) ?>

</div>
