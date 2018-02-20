<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CatEstados;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntLocalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localidades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(Yii::$app->user->identity->txt_auth_item == "abogado"){ ?>
        <p>
            <?= Html::a('Crear Localidades', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php } ?>

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
            /*[
                'attribute' => 'Usuarios',
                'format' => 'raw',
                'value' => function($data){
                    $relLocalidades = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$data->id_localidad])->all();
                    return count($relLocalidades);
                    /*foreach($relLocalidades as $relLocalidad){
                        $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])-one();
                    }*/
            /*    }
            ],*/
            [
                'attribute' => 'Asignar cliente',
                'format' => 'raw',                
                'value' => function($data){
                    return Html::activeDropDownList($data, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
                        ->where(['!=', 'txt_auth_item', 'super-admin'])
                        /*->andWhere(['txt_auth_item'=>'usuario-cliente'])
                        ->where(['id_usuario'=>$data->id_usuario])*/
                        ->andWhere(['id_status'=>2])
                        ->orderBy('txt_username')
                        ->asArray()
                        ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$data->id_localidad, 'class' => 'select select-'.$data->id_localidad, 'data-idLoc' => $data->id_localidad, 'prompt' => 'Seleccionar cliente']);
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

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>Tareas asignadas</h3>
            </div>
            <?php if($tareas){ ?>
                <div class="panel-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProviderTarea,
                    'filterModel' => $searchModelTarea,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
            
                        //'id_tarea',
                        //'id_usuario',
                        //'id_tarea_padre',
                        //'id_localidad',
                        [
                            'attribute' => 'txt_nombre',
                            'format' => 'raw',
                            'value' => function($dataTarea){
                                return Html::a($dataTarea->txt_nombre, [
                                    'tareas/descargar', 'id' => $dataTarea->id_tarea
                                ]);
                            }
                        ],
                        //'txt_nombre',
                        'txt_descripcion:ntext',
                        /*[
                            'attribute' => 'Asignar usuario',
                            'format' => 'raw',                
                            'value' => function($data){
                                return Html::activeDropDownList($data, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
                                ->where(['!=', 'txt_auth_item', 'super-admin'])
                                ->orderBy('txt_username')
                                ->asArray()
                                ->all(), 'id_usuario', 'txt_username'),['id' => "tarea-".$data->id_tarea, 'class' => 'select-tarea select-tarea-'.$data->id_tarea, 'data-idTar' => $data->id_tarea, 'prompt' => 'Seleccionar usuario']);
                                /*foreach($relLocalidades as $relLocalidad){
                                    $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])-one();
                                }*/
                        /*    }
                        ],*/
                        //'fch_creacion',
                        //'fch_due_date',
                        //'b_completa',
            
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                </div>
            <?php }else{ ?>
                <div class="panel-body">
                    <p>No hay tareas asignadas</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("

$(document).ready(function(){
    var basePath = 'http://localhost/gestor-localidades/web/';
    $('.select').on('change', function(){
        //console.log('cambio select');
        var idLoc = $(this).data('idloc');
        var idUser = $(this).val();
        $.ajax({
            url: basePath+'localidades/asignar-usuarios',
            data: {idL: idLoc, idU: idUser},
            dataType: 'json',
            type: 'POST',
            success: function(resp){
                if(resp.status == 'success'){
                    console.log('Asignacion correcta');
                }
            }
        });
    });
});

", View::POS_END );

?>
