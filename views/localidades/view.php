<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CatEstados;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\web\View;
use app\models\EntEstatus;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = $model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-view">

    

    <p>
        <?php //if(Yii::$app->user->identity->txt_auth_item == "abogado"){ ?>
            <?= Html::a('Actualizar', ['update', 'id' => $model->id_localidad], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Eliminar', ['delete', 'id' => $model->id_localidad], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php //} ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Estado',
                'value' => function($data){
                    $estado = CatEstados::find()->where(['id_estado'=>$data->id_estado])->one();
                    return $estado->txt_nombre;
                }
            ],
            /*[
                'label' => 'Usuario',
                'value' => function($data){
                    $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])->one();
                    return $user->txt_username;
                }
            ],*/
            'txt_nombre',
            'txt_arrendador',
            'txt_beneficiario',
            'txt_calle',
            'txt_colonia',
            'txt_municipio',
            'txt_cp',
            //'txt_estatus:ntext',
            [
                'label' => 'Estatus',
                'format' => 'raw',
                'value' => function($data){
                    $estatus = EntEstatus::find()->where(['id_localidad'=>$data->id_localidad])->orderBy('fch_creacion')->all();
                    $arr = "";
                    foreach ($estatus as $est){
                        $arr .= $est->txt_estatus . "<br/>";
                    }
                    return $arr;
                }
            ],
            'txt_antecedentes:ntext',
            'num_renta_actual',
            'num_incremento_autorizado',
            [
                'label' => 'Fecha Vencimiento Contratro',
                'value' => function($data){
                    return Utils::changeFormatDate($data->fch_vencimiento_contratro);
                }
            ],
            [
                'label' => 'Fecha Creacion',
                'value' => function($data){
                    return Utils::changeFormatDate($data->fch_creacion);
                }
            ],
            [
                'label' => 'Fecha Asignacion',
                'value' => function($data){
                    return Utils::changeFormatDate($data->fch_asignacion);
                }
            ],
            [
                'label' => 'Problemas Acceso',
                'value' => function($data){
                    if($data->b_problemas_acceso == 0){
                        return "No";
                    }else{
                        return "Si";
                    }
                }
            ],
            /*[
                'label' => 'Archivada',
                'value' => function($data){
                    if($data->b_archivada == 0){
                        return "No";
                    }else{
                        return "Si";
                    }
                }
            ]*/
        ],
    ]) ?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>Clientes asignados</h3>
            </div>
            <?php if($userRel){ ?>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Apellido paterno</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($userRel as $user){ 
                                $usuario = EntUsuarios::find()->where(['id_usuario'=>$user->id_usuario])->one();    
                            ?>
                                <tr>
                                    <td><?= $usuario->txt_username ?></td>
                                    <td><?= $usuario->txt_apellido_paterno ?></td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            <?php }else{ ?>
                <div class="panel-body">
                    <p>No hay clientes asignados</p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if(Yii::$app->user->identity->txt_auth_item == "abogado"){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3>Tareas asignadas</h3>
                </div>
                <?php if($tareas){ ?>
                    <div class="panel-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                
                            //'id_tarea',
                            //'id_usuario',
                            //'id_tarea_padre',
                            //'id_localidad',
                            [
                                'attribute' => 'txt_nombre',
                                'format' => 'raw',
                                'value' => function($data){
                                    return Html::a($data->txt_nombre, [
                                        'tareas/view', 'id' => $data->id_tarea
                                    ]);
                                }
                            ],
                            [
                                'attribute' => 'ver archivo',
                                'format' => 'raw',
                                'value' => function($data){
                                    if($data->txt_path){
                                        return Html::a('Descargar', [
                                            'tareas/descargar', 'id' => $data->id_tarea,
                                        ]);
                                    }else{
                                        return "<p>No se a subido archivo</p>";
                                    }
                                }
                            ],
                            //'txt_nombre',
                            'txt_descripcion:ntext',
                            [
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
                                }
                            ],
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
    <p>
        <?= Html::a('Crear Tarea', ['tareas/create', 'idLoc' => $model->id_localidad], ['class' => 'btn btn-success']) ?>
    </p>
<?php } ?>

<?php
$this->registerJs("

$(document).ready(function(){
    $('.select-tarea').on('change', function(){
        //console.log('cambio select');
        var idTar = $(this).data('idtar');
        var idUser = $(this).val();
        $.ajax({
            url: '".Yii::$app->urlManager->createAbsoluteUrl(['localidades/asignar-usuarios-tareas'])."',
            data: {idT: idTar, idU: idUser},
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

<!-- <div class="ent-localidades-form">
    <?php /*$form = ActiveForm::begin([
        'action' => ['localidades/asignar-usuarios'],
        'options' => ['method' => 'post']
    ]); */?>

    <?php // $form->field($relUserLoc, 'id_usuario')->dropDownList(ArrayHelper::map(EntUsuarios::find()->where(['txt_auth_item'=>'cliente'])->andWhere(['not in', 'id_usuario', $idUsersRel])->orderBy('txt_username')->asArray()->all(), 'id_usuario', 'txt_username'),['prompt' => 'Seleccionar usuario']) ?>

    <?php // $form->field($relUserLoc, 'id_localidad')->hiddenInput(['value' => $model->id_localidad])->label(false) ?>

    <div class="form-group">
        <?php // Html::submitButton('Asignar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php // ActiveForm::end(); ?>
</div> -->
