<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CatEstados;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ListView;

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
            <div class="row">
                <div class="col-md-3">
                    <h3 class="panel-search-title">Listado de localidades</h3>
                </div>
                <div class="col-md-9">
                        
                    <?= $this->render('_search', [
                        'model' => $model,
                        //'estatus' => $estatus            
                    ]) ?>

                    
                    <?php if(Yii::$app->user->identity->txt_auth_item == "abogado"){ ?>
                        <?= Html::a('<i class="icon wb-plus"></i> Crear Localidades', ['create'], ['class' => 'btn btn-success btn-add']) ?>
                    <?php } ?>
                 
                </div>
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

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemLocalidades',
                'options' => [
                    'class'=>"panel-listado-body"
                ],
                'layout' => '{items}{summary}{pager}',
            ]);?>

        </div>
    </div>
</div>

<div class="panel panel-tareas-asignadas">

    <div class="panel-tareas-asignadas-head">
        <h3>Tareas asignadas</h3>
    </div>
                
    <?php if($tareas){ ?>
        
        <?= ListView::widget([
            'dataProvider' => $dataProviderTarea,
            'itemView' => '_item',
            'options' => [
                'class'=>"panel-tareas-asignadas-body"
            ],
            'layout' => '{items}{summary}{pager}',
        ]); ?>
        </div>
    <?php }else{ ?>
        <div class="panel-tareas-asignadas-body-null">
            <p>No hay tareas asignadas</p>
        </div>
    <?php } ?>

</div>
<?php  

$this->registerJs("

$(document).ready(function(){
    $('.select').on('change', function(){
        console.log('cambio select--'+$(this).data('idloc')+'--'+$(this).val());
        var idLoc = $(this).data('idloc');
        var idUser = $(this).val();
        $.ajax({
            url: '".Yii::$app->urlManager->createAbsoluteUrl(['localidades/asignar-usuarios-tareas'])."',
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
