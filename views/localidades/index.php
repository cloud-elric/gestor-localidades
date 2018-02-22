<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CatEstados;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntLocalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localidades';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Panel -->
<div class="panel panel-localidades">
    <div class="panel-body">

        <div class="panel-search">
            <h3 class="panel-search-title">Listado de localidades</h3>
            <div class="panel-search-int">
                <form class="panel-search-form">
                <input type="text" class="panel-search-form-select" placeholder="Buscar por nombre">
                <input type="text" class="panel-search-form-input ml-35" placeholder="Cliente">
                <input type="text" class="panel-search-form-input" placeholder="Estado">
                <input type="text" class="panel-search-form-input" placeholder="Status">
                <input type="text" class="panel-search-form-input" placeholder="Tipo">
                </form>
                <?= Html::a('<i class="icon wb-plus"></i> Crear Localidades', ['create'], ['class' => 'btn btn-success btn-add']) ?>
            </div>
            
        </div>
            
        <div class="panel-listado">
            <div class="panel-listado-head">
                <div class="panel-listado-col w-x"></div>
                <div class="panel-listado-col w-m">Nombre</div>
                <div class="panel-listado-col w-m">Última actualización</div>
                <div class="panel-listado-col w-m">Fecha de asignación</div>
                <div class="panel-listado-col w-m">Arrendedor</div>
                <div class="panel-listado-col w-m">Responsables</div>
                <div class="panel-listado-col w-s">Acciones</div>
            </div>

            <div class="panel-listado-row">
                <div class="panel-listado-col w-x"><span class="panel-listado-iden"></span></div>
                <div class="panel-listado-col w-m">Central Camionera</div>
                <div class="panel-listado-col w-m">Hoy</div>
                <div class="panel-listado-col w-m">13 - Ago -2018</div>
                <div class="panel-listado-col w-m">Carpet contractors</div>
                <div class="panel-listado-col w-m"><img class="panel-listado-img" src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt=""></div>
                <div class="panel-listado-col w-s"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>
            </div>

            <div class="panel-listado-row">
                <div class="panel-listado-col w-x"><span class="panel-listado-iden"></span></div>
                <div class="panel-listado-col w-m">Central Camionera</div>
                <div class="panel-listado-col w-m">Hoy</div>
                <div class="panel-listado-col w-m">13 - Ago -2018</div>
                <div class="panel-listado-col w-m">Carpet contractors</div>
                <div class="panel-listado-col w-m"><img class="panel-listado-img" src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt=""></div>
                <div class="panel-listado-col w-s"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>
            </div>

            <div class="panel-listado-row">
                <div class="panel-listado-col w-x"><span class="panel-listado-iden x2"></span></div>
                <div class="panel-listado-col w-m">Central Camionera</div>
                <div class="panel-listado-col w-m">Hoy</div>
                <div class="panel-listado-col w-m">13 - Ago -2018</div>
                <div class="panel-listado-col w-m">Carpet contractors</div>
                <div class="panel-listado-col w-m"><img class="panel-listado-img" src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt=""></div>
                <div class="panel-listado-col w-s"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>
            </div>

        </div>

    </div>
</div>

<div class="ent-localidades-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
