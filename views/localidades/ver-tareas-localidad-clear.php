<?php

use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\bootstrap\Html;

use app\assets\AppAsset;
use yii\helpers\Url;

use yii\widgets\ActiveForm;
use yii\web\View;
use app\models\WrkUsuariosTareas;

$usuario = EntUsuarios::getUsuarioLogueado();
$isAbogado = $usuario->txt_auth_item == ConstantesWeb::ABOGADO;
$isColaborador = $usuario->txt_auth_item == ConstantesWeb::COLABORADOR;

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
                                            <li class="list-group-item js-tarea-<?=$tarea->id_tarea?>" data-tareakey="<?=$tarea->id_tarea?>">
                                                
                                                <div class="w-full">

                                                    <div class="row row-no-border js_descargar_archivo-<?=$tarea->id_tarea?>">

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <?php
                                                            if($isAbogado){
                                                                $relTareaUsuario = WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->all();

                                                            ?>
                                                                
                                                                <div class="label-check">Nombre</div>

                                                                <div class="checkbox-custom checkbox-warning">
                                                                    
                                                                    <input type="checkbox" class="js-completar-tarea" data-token="<?=$tarea->id_tarea?>" name="checkbox" <?=$tarea->b_completa?"checked":""?>>
                                                                    <?php
                                                                    $form1 = ActiveForm::begin(['id'=>'form-tarea-nombre'.$tarea->id_tarea]);
                                                                    ?>                                                           
                                                                        <label class="task-title">
                                                                            <?= $form1->field($tarea, 'txt_nombre')->textInput(['data-id'=>$tarea->id_tarea, 'class'=>'form-control js-editar-nombre-tarea'])->label(false) ?>
                                                                        </label>
                                                                    <?php Html::submitButton('Guardar')?>
                                                                    <?php
                                                                    ActiveForm::end();
                                                                    ?>

                                                                    
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
                                                                <select multiple='multiple' class='plugin-selective-tareas' data-localidad="<?=$localidad->id_localidad?>" data-id='<?=$tarea->id_tarea?>' data-json='<?=$tarea->colaboradoresAsignados?>'></select> 
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>    
                                                        <div class="url_documento col-xs-2 col-sm-2 col-md-2 text-right">
                                                        <?php
                                                            if($hasArchivo){
                                                        ?>
                                                                
                                                                    <?= Html::a(' <i class="icon wb-attach-file" aria-hidden="true"></i>
                                                                                    ', ['tareas/descargar', 'id' => $tarea->id_tarea,], ['target' => '_blank', 'class' => 'btn no-pjax btn-success btn-outline']);?>
                                                               
                                                        <?php
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>

                                                    
                                                    <?php
                                                    if(($isColaborador || $isAbogado) && !$tarea->b_completa){
                                                    ?>    
                                                    <div class="row row-no-border">
                                                        <div class="col-md-12">
                                                            <?php
                                                            $tarea->scenario = 'update';
                                                            $form = ActiveForm::begin([
                                                                'id'=>'form-tarea-'.$tarea->id_tarea,
                                                                'action'=>'tareas/update?id='.$tarea->id_tarea,
                                                                'options' =>[
                                                                    'class' => 'formClass',
                                                                    'enctype' => 'multipart/form-data'
                                                                ]
                                                            ]); 
                                                            ?>
                                                            <?php
                                                            $textoGuardar = "";
                                                            if($tarea->id_tipo==ConstantesWeb::TAREA_ARCHIVO){
                                                                $textoGuardar = "Guardar archivo";
                                                            ?>
                                                                <?= $form->field($tarea, 'file')->fileInput(['data-id'=>$tarea->id_tarea, 'data-plugin'=>"dropify", 'class'=>"file_tarea"]) ?>
                                                            <?php
                                                            }else if($tarea->id_tipo==ConstantesWeb::TAREA_ABIERTO){
                                                                $textoGuardar = "Guardar";
                                                            ?>

                                                                    <?= $form->field($tarea, 'txt_tarea')->textarea(['rows' => 6, 'data-id'=>$tarea->id_tarea, 'style'=>"resize:none", 'placeholder'=>"Descripción"])->label(false) ?>  
                                                                

                                                            <?php
                                                            }
                                                            ?>
                                                            <?= $form->field($tarea, 'id_tipo')->hiddenInput(['class'=>'tipo-'.$tarea->id_tarea])->label(false) ?>

                                                            <div class="form-group text-right">
                                                                <?=Html::submitButton("<span class='ladda-label'><i class='icon wb-file' aria-hidden='true'></i>".$textoGuardar."</span>", ["data-id"=>$tarea->id_tarea, "style"=>"display:none;", "data-style"=>'zoom-in', "class"=>"btn ladda-button btn-save-texto mt-20 submit_tarea"]);?>
                                                            </div>
                                                            
                                                            <?php
                                                            ActiveForm::end();
                                                            ?>
                                                        </div>
                                                    </div>
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
                                                </div>
                                                <?php
                                                if(!$relTareaUsuario && $tarea->txt_tarea == null && $tarea->txt_path == null){
                                                ?>
                                                    <button class="btn btn-delete-tarea js_btn_eliminar_tarea js_btn_eliminar_tarea-<?= $tarea->id_tarea ?>" data-id="<?= $tarea->id_tarea ?>">Eliminar tarea</button>
                                                <?php
                                                }
                                                ?>                
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
