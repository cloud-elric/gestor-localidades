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
use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\models\WrkUsuarioUsuarios;
use app\models\WrkUsuariosTareas;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = $model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user->identity;
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

<?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO || Yii::$app->user->identity->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3>Tareas</h3>
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
                                    if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::CLIENTE){
                                        $user = Yii::$app->user->identity;
                                        $grupoTrabajo = WrkUsuarioUsuarios::find()->where(['id_usuario_padre'=>$user->id_usuario])->select('id_usuario_hijo')->asArray();
                                        return Html::activeDropDownList($data, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
                                        /*->where(['!=', 'txt_auth_item', ConstantesWeb::SUPER_ADMIN])
                                        ->andWhere(['!=', 'txt_auth_item', ConstantesWeb::ABOGADO])*/
                                        ->where(['in', 'id_usuario', $grupoTrabajo])                                    
                                        ->orderBy('txt_username')
                                        ->asArray()
                                        ->all(), 'id_usuario', 'txt_username'),['id' => "tarea-".$data->id_tarea, 'class' => 'select-tarea select-tarea-'.$data->id_tarea, 'data-idTar' => $data->id_tarea, 'prompt' => 'Seleccionar usuario']);
                                        /*foreach($relLocalidades as $relLocalidad){
                                            $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])-one();
                                        }*/
                                    }else{
                                        /*$idUser = WrkUsuariosTareas::find()->where(['id_tarea'=>$data->id_tarea])->select('id_usuario');
                                        if(!$idUser){
                                            $user = EntUsuarios::find()->where(['id_usuario'=>$idUser])->one();
                                            return Html::img($user->txt_imagen, ['alt'=>$user->txt_email, 'style'=>'position: relative;
                                                display: inline-block;
                                                width: 40px;
                                                white-space: nowrap;
                                                border-radius: 1000px;
                                                vertical-align: bottom;']);
                                        }*/
                                        return Html::img('imagen', ['alt'=>'imagen']);
                                    }
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
        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
            <?= Html::a('Crear Tarea', ['tareas/create', 'idLoc' => $model->id_localidad], ['class' => 'btn btn-success']) ?>
        <?php } ?>
    </p>
<?php } ?>

<?php if(Yii::$app->user->identity->txt_auth_item != ConstantesWeb::ABOGADO){?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3>Tareas</h3>
                </div>
                <?php if($tareas){ ?>
                    <div class="panel-body">
                    <?= ListView::widget([
                        'dataProvider' => $dataProviderTarea,
                        'itemView' => '_item',
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

    $('#wrktareas-file').on('change', function(){
        $('#btnGuardarArchivo').css('display', 'block');
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
