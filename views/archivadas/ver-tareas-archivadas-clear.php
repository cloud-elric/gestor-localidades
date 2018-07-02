<?php

use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\bootstrap\Html;

use app\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\WrkUsuariosTareasArchivadas;

$usuario = EntUsuarios::getUsuarioLogueado();
$isAbogado = $usuario->txt_auth_item == ConstantesWeb::ABOGADO;
$isColaborador = $usuario->txt_auth_item == ConstantesWeb::COLABORADOR;

?>
<div style="display:none;" id="json-colaboradores-<?=$localidad->id_localidad?>" data-colaboradores='<?=$jsonAgregar?>'></div>
<header class="slidePanel-header ryg-header">
  <div class="slidePanel-actions" aria-label="actions" role="group">
    <button type="button" class="btn btn-pure btn-inverse slidePanel-close actions-top icon wb-close"
      aria-hidden="true"></button>
  </div>
  <h1>Tareas: <?=$localidad->txt_nombre?></h1>
</header>

<div class="page ryg-page">
    <div class="page-content">
        <div class="ent-localidades-view">
            <div class="ent-localidades-view-body">
                <div class="ent-localidades-view-panel">
                   
                    <div class="row row-no-border">
                        <div class="col-md-12 col">
                            
                            <div class="ent-localidades-view-panel-int">

                                <div class="row row-no-border">
                                    
                                    <div class="col-md-12">
                                        <ul class="list-group taskboard-list ui-sortable js-tareas-contenedor-<?=$localidad->id_localidad?>">
                                            <?php 
                                            if(count($tareas)==0){
                                                echo "<h2>No hay tareas</h2>";
                                            }

                                            foreach($tareas as $tarea){
                                            
                                            $hasArchivo = $tarea->id_tipo==ConstantesWeb::TAREA_ARCHIVO && $tarea->txt_path;
                                            ?>
                                            <li class="list-group-item" data-tareakey="<?=$tarea->id_tarea?>">
                                                
                                                <div class="w-full">

                                                    <div class="row row-no-border js_descargar_archivo-<?=$tarea->id_tarea?>">

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <?php
                                                            if($isAbogado){
                                                            ?>
                                                                <div>
                                                                    <label class="task-title"><?=$tarea->txt_nombre?></label>
                                                                </div>
                                                            <?php
                                                            }else{?>
                                                                <p><?=$tarea->txt_nombre?></p>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <?php
                                                        if($isAbogado){
                                                        ?>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 text-left addMember-cont">
                                                                <?php
                                                                $useRel = WrkUsuariosTareasArchivadas::find()->where(['id_tarea'=>$tarea->id_tarea])->one();
                                                                if($useRel){?>
                                                                    <select multiple='multiple' class='plugin-selective-tareas' data-json='<?=$tarea->colaboradoresAsignados?>'></select>
                                                                <?php } ?>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>    
                                                        <div class="url_documento col-xs-2 col-sm-2 col-md-2 text-right">
                                                        <?php
                                                            if($hasArchivo){
                                                        ?>
                                                                
                                                                    <?= Html::a(' <i class="icon wb-attach-file" aria-hidden="true"></i>
                                                                                    ', ['tareas/descargar-desde-archivada', 'id' => $tarea->id_tarea,], ['target' => '_blank', 'class' => 'btn no-pjax btn-success btn-outline']);?>
                                                               
                                                        <?php
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>

                                                    
                                                    <?php
                                                    if($isColaborador && !$tarea->b_completa){
                                                    ?>    
                                                    
                                                    <?php
                                                    }else if($tarea->b_completa){
                                                    ?>
                                                    <div class="row row-no-border">
                                                        <div class="col-md-12">
                                                            <p>Tarea completa</p>
                                                        </div>
                                                    </div> 
                                                    <?php
                                                    }
                                                    ?> 
                                                    
                                                    <?php
                                                    if($isAbogado && $tarea->txt_tarea){?>
                                                    <div class="row row-no-border">
                                                        <div class="col-md-12">
                                                            <p><?=$tarea->txt_tarea?></p>
                                                            </div>
                                                    </div>         
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                
                                            </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>   
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
