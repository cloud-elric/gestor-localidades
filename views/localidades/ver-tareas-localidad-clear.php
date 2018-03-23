<?php

use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\bootstrap\Html;

use app\assets\AppAsset;

use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\WrkUsuariosTareas;

$usuario = EntUsuarios::getIdentity();
$isAbogado = $usuario->txt_auth_item == ConstantesWeb::ABOGADO;

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.css',
    ['depends' => [AppAsset::className()]]
  ); 

  $this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.min.js',
    ['depends' => [AppAsset::className()]]
);

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
                                <?php
                                if($isAbogado){
                                ?>
                                <!--Botón para generar nueva tarea-->
                                <div class="row row-no-border">
                                    <div class="col-sm-6 offset-sm-6 col-md-6 offset-md-6">
                                        <button data-token="<?=$localidad->id_localidad?>" class="btn btn-warning btn-block js-open-modal-tarea">
                                            <i class="icon wb-plus" aria-hidden="true"></i> Agregar tarea
                                        </button>
                                    </div>
                                </div>
                                <!--Botón para generar nueva tarea fin-->
                                <?php
                                }
                                ?>

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

                                                    <div class="row row-no-border">

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <?php
                                                            if($isAbogado){
                                                            ?>
                                                            <div class="checkbox-custom checkbox-success">
                                                                <input type="checkbox" name="checkbox">
                                                               
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
                                                            <div class="col-xs-2 col-sm-2 col-md-2 text-right">
                                                                <select multiple='multiple' class='plugin-selective-tareas' data-localidad="<?=$localidad->id_localidad?>" data-id='<?=$tarea->id_tarea?>' data-json='<?=$tarea->colaboradoresAsignados?>'></select> 
                                                            </div>

                                                            <?php
                                                            if($hasArchivo){
                                                            ?>
                                                            <div class="col-xs-2 col-sm-2 col-md-2 text-right">
                                                                <?= Html::a(' <i class="icon wb-attach-file" aria-hidden="true"></i>
                                                                                ', ['tareas/descargar', 'id' => $tarea->id_tarea,], ['target' => '_blank', 'class' => 'btn btn-success btn-outline']);?>
                                                            </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                        ?>    
                                                    </div>
                                                   
                                                    <?php
                                                    if(!$isAbogado){
                                                    ?>    
                                                    <div class="row row-no-border">
                                                        <div class="col-md-12">
                                                            <?php
                                                            
                                                            $form = ActiveForm::begin([
                                                                'id'=>'form-tarea-'.$tarea->id_tarea,
                                                                'action'=>'tareas/create?idTar='.$tarea->id_tarea,
                                                            ]); 
                                                            
                                                            if($tarea->id_tipo==ConstantesWeb::TAREA_ARCHIVO){
                                                            ?>
                                                            <input type="file" id="input-file-now" data-plugin="dropify"   data-default-file="">
                                                            <?php
                                                            }else if($tarea->id_tipo==ConstantesWeb::TAREA_ABIERTO){
                                                            ?>
                                                            <div class="form-group mb-0">
                                                                <textarea style="resize:none" class="form-control" placeholder="Descripción"></textarea>   
                                                            </div>

                                                            <?php
                                                            }
                                                            ?>

                                                            <div class="form-group text-right">
                                                                <?=Html::submitButton("<i class='icon wb-file' aria-hidden='true'></i> Guardar tarea", ["id"=>"btn-guardar-form-tarea-".$tarea->id_tarea, "class"=>"btn btn-warning mt-20"]);?>
                                                            </div>
                                                            
                                                            <?php
                                                            ActiveForm::end();
                                                            ?>
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
