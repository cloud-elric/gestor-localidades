<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CatEstados;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\EntUsuarios;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntLocalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localidades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Localidades', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_localidad',
            [
                'attribute' => 'id_estado',
                'format' => 'raw',
                'value' => function($data){
                    $estado = CatEstados::find()->where(['id_estado'=>$data->id_estado])->one();
                    return $estado->txt_nombre;
                }
            ],
            //'id_usuario',
            //'txt_token',
            [
                'attribute' => 'txt_nombre',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->txt_nombre, [
                        'localidades/view', 'id' => $data->id_localidad
                    ]);
                }
            ],
            'txt_arrendador',
            [
                'attribute' => 'Usuarios',
                'format' => 'raw',
                'value' => function($data){
                    $relLocalidades = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$data->id_localidad])->all();
                    return count($relLocalidades);
                    /*foreach($relLocalidades as $relLocalidad){
                        $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])-one();
                    }*/
                }
            ],
            //'txt_calle',
            //'txt_colonia',
            //'txt_municipio',
            //'txt_cp',
            //'txt_estatus:ntext',
            //'txt_antecedentes:ntext',
            //'num_renta_actual',
            //'num_incremento_autorizado',
            //'fch_vencimiento_contratro',
            //'fch_creacion',
            //'fch_asignacion',
            //'b_problemas_acceso',
            //'b_archivada',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
