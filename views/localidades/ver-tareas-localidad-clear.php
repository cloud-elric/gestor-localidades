<?php

use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\bootstrap\Html;
use yii\web\View;
use app\assets\AppAsset;

$usuario = EntUsuarios::getIdentity();

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
  <h1>Tareas: <?=$localidad->txt_nombre?></h1>
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
                                            <?php 
                                            if(count($tareas)==0){
                                                echo "<h2>No hay tareas</h2>";
                                            }


                                            foreach($tareas as $tarea){
                                            $hasArchivo = $tarea->id_tipo==ConstantesWeb::TAREA_ARCHIVO && $tarea->txt_path;
                                            ?>
                                            <li class="list-group-item">
                                                <div class="checkbox-custom checkbox-primary">
                                                    <input type="checkbox" name="checkbox">
                                                    <label class="task-title"><?=$tarea->txt_nombre?></label>
                                                </div>
                                                <div class="w-full">
                                                    
                                                    <div class="task-badges">
                                                        <?= Html::a('<span class="task-badge task-badge-attachments icon wb-paperclip">
                                                                    Descargar archivo
                                                                    </span>', ['tareas/descargar', 'id' => $tarea->id_tarea,], ['target' => '_blank']);?>
    
                                                    </div>
                                                   
                                                    <div class="col-md-6">
                                                        <?php
                                                        if($tarea->id_tipo==ConstantesWeb::TAREA_ARCHIVO){
                                                        ?>
                                                        <input type="file" id="input-file-now" data-plugin="dropify" data-allowed-file-extensions="xlsx" data-default-file="">
                                                        <?php
                                                        }else if($tarea->id_tipo==ConstantesWeb::TAREA_ABIERTO){
                                                        ?>
                                                        <textarea></textarea>   
                                                        <?php
                                                        }
                                                        ?>
                                                    </div> 
                                                </div>
                                            </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>   
                                </div>
                                <?php
                                if($usuario->txt_auth_item==ConstantesWeb::ABOGADO){
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="">

                                        </button>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>