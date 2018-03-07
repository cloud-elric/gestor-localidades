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
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = $model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.css',
    ['depends' => [AppAsset::className()]]
  );  
  
$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.min.js',
    ['depends' => [AppAsset::className()]]
);

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

<!-- ************************************************************************************************************************************* -->

<?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
    <div class="panel panel-localidades">
        <div class="panel-body">
            <div class="panel-listado">
                <div class="panel-listado-head">
                    <div class="panel-listado-col w-x"></div>
                    <div class="panel-listado-col w-m">Nombre</div>
                    <div class="panel-listado-col w-m">Ver Archivo</div>
                    <div class="panel-listado-col w-m">Descripcion</div>
                    <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
                        <div class="panel-listado-col w-m">Responsables</div>
                    <?php } ?>
                    <div class="panel-listado-col w-s">Acciones</div>
                </div>

                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_itemTareas',
                ]);?>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
    <?= Html::a('Crear Tarea', ['tareas/create', 'idLoc' => $model->id_localidad], ['class' => 'btn btn-success']) ?>
<?php } ?>

<!-- ************************************************************************************************************************************* -->


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


<?php
$this->registerJs("
$(document).ready(function(){

    $('#wrktareas-file').on('change', function(){
        $('#btnGuardarArchivo').css('display', 'block');
    });

    var member = ".$jsonAgregar.";

    $('.plugin-selective').each(function () {
        var elemento = $(this);
        elemento.selective({
          namespace: 'addMember',
          selected: elemento.data('json'),
          local: member,
          onAfterSelected: function(e){
              //alert(elemento.val());
          },
          onAfterItemAdd: function(e){
            //alert(elemento.val());
            //alert(elemento.data('id'));
            var idTar = elemento.data('idtar');
            var idUser = elemento.val();

            $.ajax({
                url: '".Yii::$app->urlManager->createAbsoluteUrl(['localidades/asignar-usuarios-tareas'])."',
                data: {idT: idTar, idU: idUser},
                dataType: 'json',
                type: 'POST',
                success: function(resp){
                    if(resp.status == 'success'){
                        console.log('Asignacion de tarea correcta');
                    }
                }
            });
          },
          onAfterItemRemove: function(e){
            var idLoc = elemento.data('id');
            var idUser = elemento.val();
            if(!idUser){
                idUser = -1;
            }

            $.ajax({
                url: '".Yii::$app->urlManager->createAbsoluteUrl(['localidades/asignar-usuarios-eliminar'])."',
                data: {idL: idLoc, idU: idUser},
                dataType: 'json',
                type: 'POST',
                success: function(resp){
                    if(resp.status == 'success'){
                        console.log('Eliminacion correcta');
                    }
                }
            });
          },
          buildFromHtml: false,
          tpl: {
            optionValue: function optionValue(data) {
              return data.id;
            },
            frame: function frame() {
              return '<div class=\"' + this.namespace + '\">                ' + this.options.tpl.items.call(this) + '                <div class=\"' + this.namespace + '-trigger\">                ' + this.options.tpl.triggerButton.call(this) + '                <div class=\"' + this.namespace + '-trigger-dropdown\">                ' + this.options.tpl.list.call(this) + '                </div>                </div>                </div>';
        
              // i++;
            },
            triggerButton: function triggerButton() {
              return '<div class=\"' + this.namespace + '-trigger-button\"><i class=\"wb-plus\"></i></div>';
            },
            listItem: function listItem(data) {
              return '<li class=\"' + this.namespace + '-list-item\"><img class=\"avatar\" src=\"' + data.avatar + '\">' + data.name + '</li>';
            },
            item: function item(data) {
              return '<li class=\"' + this.namespace + '-item\"><img class=\"avatar\" src=\"' + data.avatar + '\" title=\"' + data.name + '\">' + this.options.tpl.itemRemove.call(this) + '</li>';
            },
            itemRemove: function itemRemove() {
              return '<span class=\"' + this.namespace + '-remove\"><i class=\"wb-minus-circle\"></i></span>';
            },
            option: function option(data) {
              return '<option value=\"' + this.options.tpl.optionValue.call(this, data) + '\">' + data.name + '</option>';
            }
          }
        });
    });
});
", View::POS_END );

?>
