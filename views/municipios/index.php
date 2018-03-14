<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CatMunicipiosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cat Municipios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-municipios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cat Municipios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_municipio',
            'id_estado',
            'id_area',
            'txt_nombre',
            'txt_descripcion',
            //'num_latitud',
            //'num_longitud',
            //'b_habilitado:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
