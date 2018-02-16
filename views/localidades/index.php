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
                    return Html::activeDropDownList($data, 'id_usuario', ArrayHelper::map(EntUsuarios::find()->where(['txt_auth_item'=>'cliente'])->orderBy('txt_username')->asArray()->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$data->id_localidad, 'class' => 'select select-'.$data->id_localidad, 'data-idLoc' => $data->id_localidad, 'prompt' => 'Seleccionar cliente']);
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
