<?php

use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\bootstrap\Html;
use yii\web\View;
use app\assets\AppAsset;

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.css',
    ['depends' => [AppAsset::className()]]
  ); 

  $this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.min.js',
    ['depends' => [AppAsset::className()]]
);

?>
<header class="slidePanel-header ryg-header">
  <div class="slidePanel-actions" aria-label="actions" role="group">
    <button type="button" class="btn btn-pure btn-inverse slidePanel-close actions-top icon wb-close"
      aria-hidden="true"></button>
  </div>
  <h1>Tareas: <?=$model->txt_nombre?></h1>
</header>

<div class="page ryg-page">
    <div class="page-content">
        <div class="ent-localidades-view">
            <div class="ent-localidades-view-body">
                <div class="ent-localidades-view-panel">
                    <div class="row">
                        <div class="col-md-12 col">
                            
                            <div class="ent-localidades-view-panel-int">
                                <div class="row">

                                   
                                    <div class="col-md-12">
                                        <ul class="list-group taskboard-list ui-sortable">
                                            <li class="list-group-item">
                                                <div class="checkbox-custom checkbox-primary">
                                                    <input type="checkbox" name="checkbox">
                                                        <label class="task-title">Perspecta historiae studiis</label>
                                                </div>
                                                <div class="w-full">
                                                    <div class="task-badges">
                                                        <span class="task-badge task-badge-subtask icon wb-list-bulleted">1/2</span>
                                                        <span class="task-badge task-badge-attachments icon wb-paperclip">2</span>
                                                        <span class="task-badge task-badge-comments icon wb-chat">2</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="file" id="input-file-now" data-plugin="dropify" data-allowed-file-extensions="xlsx" data-default-file="">
                                                    </div>
                                                    
                                                </div>
                                            </li>
                                            
                                        </ul>
                                    </div>     


                                    <div class="col-md-12">
                                        <div class="card-block p-0">
                                            <ul class="project-team-items clearfix">
                                                <li class="team-item">
                                                    <a href="#" class="avatar avatar-sm my-5">
                                                    <img src="http://via.placeholder.com/30x30">
                                                    </a>
                                                </li>
                                                <li class="team-item item-divider">
                                                    <i class="icon wb-chevron-right mr-0"></i>
                                                </li>
                                                <li class="team-item">
                                                    <a class="avatar avatar-sm my-5 mr-5" data-member-id="m_1">
                                                        <img src="http://via.placeholder.com/30x30" />
                                                    </a>
                                                    <a class="avatar avatar-sm my-5 mr-5" data-member-id="m_2">
                                                        <img src="http://via.placeholder.com/30x30" />
                                                    </a>
                                                    <a class="avatar avatar-sm my-5 mr-5" data-member-id="m_3">
                                                        <img src="http://via.placeholder.com/30x30" />
                                                    </a>
                                                    <a class="avatar avatar-sm my-5 mr-5" data-member-id="m_4">
                                                        <img src="http://via.placeholder.com/30x30" />
                                                    </a>
                                                    <a class="avatar avatar-sm my-5 mr-5" data-member-id="m_5">
                                                        <img src="http://via.placeholder.com/30x30" />
                                                    </a>
                                                    <a class="avatar avatar-sm my-5 mr-5" data-member-id="m_6">
                                                        <img src="http://via.placeholder.com/30x30" />
                                                    </a>
                                                </li>       
                                            </ul>
                                        </div>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="card-block project-checklist p-0">
                                            <h4 class="project-checklist-title project-option-title">
                                                <button type="button" class="btn btn-pure btn-default icon wb-trash btn-trash"></button>
                                                Title
                                            </h4>

                                            <div class="progress progress-xs" data-plugin="progress" data-labeltype="percentage">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemax="100"
                                                aria-valuemin="0" aria-valuenow="50">
                                                </div>
                                                <div class="progress-label progress-percent blue-grey-400"></div>
                                            </div>

                                            <!-- <div class="checkbox-custom checkbox-primary mb-15">
                                                <input type="checkbox" name="checkbox">
                                                <label class="title">Checklist item1</label>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary mb-15">
                                                <input type="checkbox" checked="checked" name="checkbox">
                                                <label class="title">Checklist item2</label>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary mb-15">
                                                <input type="checkbox" name="checkbox" disabled>
                                                <label class="title">Checklist item3</label>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary mb-15">
                                                <input type="checkbox" checked="checked" name="checkbox" disabled>
                                                <label class="title">Checklist item4</label>
                                            </div> -->
                                            <!-- <button type="button" class="btn btn-default btn-add">Add checklist item</button>
                                            <div class="project-checklist-add bg-blue-grey-200">
                                                <form>
                                                    <input type="text" name="title" class="form-control">
                                                    <div class="operations">
                                                    <button type="button" class="btn btn-primary">Add</button>
                                                    <button type="button" class="btn btn-pure btn-default icon wb-trash p-0 btn-trash ml-10"></button>
                                                    </div>
                                                </form>
                                            </div> -->
                                        </div>
                    
                                    </div>


                                    <div class="col-md-12">

                                        <div class="card-block p-0">
                                            <h4 class="project-option-title">Uploading</h4>
                                            <form class="upload-form" id="projectUploadForm" method="POST">
                                                <input type="file" id="inputUpload" name="files[]" multiple="" />
                                                <div class="uploader-inline">
                                                    <p class="upload-instructions">Click Or Drop Files To Upload.</p>
                                                </div>
                                                <div class="file-wrap container-fluid">
                                                    <div class="file-list row"></div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>


                                </div>

                            </div>

                        </div>

                        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                        <div class="col-md-6 col">
                            
                            <h3>Abo</h3>

                            <div class="ent-localidades-view-panel-int">

                                <div class="row">
                                    <div class="col-md-12">
                                        
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
                                    <!-- <div class="col-md-6">
                                        sdf
                                    </div> -->
                                </div>
                            
                            </div>

                        </div>
                        <?php } ?>

                        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
                        <div class="col-md-6 col">

                            <h3>Clientes</h3>

                            <div class="ent-localidades-view-panel-int">
                                
                                <div class="row">
                                    <!-- <div class="col-md-6">
                                        Algo
                                    </div> -->
                                    <div class="col-md-12">
                                        
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


                                    </div>

                                </div>

                            </div>

                        </div>
                        <?php } ?>

                        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                            <?= Html::a('Crear Tarea', ['tareas/create', 'idLoc' => $model->id_localidad], ['class' => 'btn btn-success']) ?>
                        <?php  } ?>




                        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                            <!-- <div class="row">
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
                            </div> -->
                        <?php } ?>

                        <!-- ************************************************************************************************************************************* -->

                        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
                            <!-- <div class="panel panel-localidades">
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
                            </div> -->
                        <?php } ?>

                        <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                            <?php # Html::a('Crear Tarea', ['tareas/create', 'idLoc' => $model->id_localidad], ['class' => 'btn btn-success']) ?>
                        <?php } ?>

                        <!-- ************************************************************************************************************************************* -->


                        <!-- <div class="row">
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
                        </div> -->

                        <?php
                        $this->registerJs("
                        $(document).ready(function(){

                            $('#wrktareas-file').on('change', function(){
                                id = $(this).data('id');
                                $('#btnGuardarArchivo-'+id).css('display', 'block');
                            });

                            $('#wrktareas-txt_tarea').focus(function(){
                                id = $(this).data('id');
                                $('#btnGuardarArchivo-'+id).css('display', 'block');
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>