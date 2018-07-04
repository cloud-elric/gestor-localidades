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
use app\modules\ModUsuarios\models\Utils;

$usuario = EntUsuarios::getUsuarioLogueado();
$isAbogado = $usuario->txt_auth_item == ConstantesWeb::ABOGADO;
$isColaborador = $usuario->txt_auth_item == ConstantesWeb::COLABORADOR;
$relTareaUsuario = null;
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

                                                        <div class="col-sm-12 col-md-12 col-separacion">

                                                            <div class="tarea-fechas">
                                                                <div class="tarea-creada">
                                                                    <p class="item">Creada: 24 - JUNIO - 2018</p>
                                                                </div>
                                                                <div class="tarea-actualizacion">
                                                                    <p class="item">Última actualización: 26 - JUNIO - 2018</p>
                                                                    <p class="borrar">Borrar</p>
                                                                </div>
                                                            </div>

                                                            <?php
                                                            $form1 = ActiveForm::begin(['id'=>'form-tarea-nombre'.$tarea->id_tarea, 'options' => ['class' => 'tarea-actions form-tareas']]);
                                                            ?>
                                                                <div class="tarea-check">
                                                                    <?php
                                                                    if($isAbogado){
                                                                        $relTareaUsuario = WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->all();

                                                                    ?>
                                                                        <div class="checkbox-custom checkbox-warning">                                                    
                                                                            <input type="checkbox" id="check-nombre" class="js-completar-tarea" data-token="<?=$tarea->id_tarea?>" name="checkbox" <?=$tarea->b_completa?"checked":""?>>
                                                                            <label for="check-nombre" class="task-title" style="width:100%"></label>
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
                                                                    <div class="tarea-member addMember-cont">
                                                                        <select multiple='multiple' class='plugin-selective-tareas' data-localidad="<?=$localidad->id_localidad?>" data-id='<?=$tarea->id_tarea?>' data-json='<?=$tarea->colaboradoresAsignados?>'></select> 
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                            

                                                                <div class="form-tarea-abogado">
                                                                    <div class="form-group">
                                                                        <?= $form1->field($tarea, 'txt_nombre')->textarea(['data-id'=>$tarea->id_tarea, 'class'=>'form-control form-tarea-input js-editar-nombre-tarea'])->label(false) ?>
                                                                        <p class="form-p form-tarea-label">Algo de lorem ipsum</p>
                                                                        <div class="form-tarea-edit">
                                                                            <i class="icon wb-pencil icon-edit js-tarea-icon-edit" aria-hidden="true"></i>
                                                                            <i class="icon wb-check icon-save js-tarea-icon-save" aria-hidden="true"></i>
                                                                            <?php # Html::submitButton('Guardar')?>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <?php
                                                            ActiveForm::end();
                                                            ?>

                                                            <div class="tarea-nombre">
                                                                <h4>Nombre del archivo</h4>
                                                                <?php
                                                                if($hasArchivo){
                                                                ?>

                                                                    <?= Html::a(' <i class="icon fa-download" aria-hidden="true"></i>
                                                                    ', ['tareas/descargar', 'id' => $tarea->id_tarea,], ['target' => '_blank', 'class' => 'btn no-pjax btn-default btn-down-archive']);?>

                                                                <?php
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>

                                                        
                                                        

                                                        <div class="col-sm-12 col-md-12 col-separacion">

                                                            <?php
                                                            if(($isColaborador || $isAbogado) && !$tarea->b_completa){
                                                            ?> 

                                                                <div class="tarea-fechas">
                                                                    <div class="tarea-creada">
                                                                        <p class="item">Creada: 24 - JUNIO - 2018</p>
                                                                    </div>
                                                                    <div class="tarea-actualizacion">
                                                                        <p class="item">Última actualización: 26 - JUNIO - 2018</p>
                                                                        <p class="borrar">Borrar</p>
                                                                    </div>
                                                                </div>

                                                                <?php
                                                                $tarea->scenario = 'update';
                                                                $form = ActiveForm::begin([
                                                                    'id'=>'form-tarea-'.$tarea->id_tarea,
                                                                    'action'=>'tareas/update?id='.$tarea->id_tarea,
                                                                    'options' =>[
                                                                        'class' => 'formClass form-tarea-archive',
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
                                                                <?php
                                                                    if($isColaborador || $isAbogado){
                                                                ?>
                                                                    <?=Html::submitButton("<span class='ladda-label'><i class='icon wb-file' aria-hidden='true'></i>".$textoGuardar."</span>", ["data-id"=>$tarea->id_tarea, "style"=>"display:block;", "data-style"=>'zoom-in', "class"=>"btn ladda-button btn-save-texto btn-block mt-20 submit_tarea"]);?>
                                                                <?php
                                                                    }
                                                                ?>
                                                                </div>

                                                                <div class="form-archive">
                                                                    <p>Nombre del archivo.PDF</p>
                                                                </div>
                                                                
                                                                <?php
                                                                ActiveForm::end();
                                                                ?>
                                                        
                                                            <?php
                                                            }else if($tarea->b_completa){
                                                            ?>
                                                                <div class="tarea-completada-text">
                                                                    <p>Tarea completa</p>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                    
                                                        </div>
                                                        
                                                        




                                                        <!-- CODIGO DE ANTES -->
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <?php
                                                            if($isAbogado){
                                                                $relTareaUsuario = WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->all();

                                                            ?>

                                                                
                                                                
                                                                <div class="label-check">Nombre</div>
                                                                    <div class="col-md-12">
                                                                        <?php
                                                                        $form1 = ActiveForm::begin(['id'=>'form-tarea-nombre'.$tarea->id_tarea, 'options' => ['class' => 'form-tareas']]);
                                                                        ?>
                                                                            <div class="checkbox-custom checkbox-warning">                                                    
                                                                                <input type="checkbox" id="check-nombre" class="js-completar-tarea" data-token="<?=$tarea->id_tarea?>" name="checkbox" <?=$tarea->b_completa?"checked":""?>>
                                                                                <label for="check-nombre" class="task-title" style="width:100%">
                                                                                    <?= $form1->field($tarea, 'txt_nombre')->textarea(['data-id'=>$tarea->id_tarea, 'class'=>'form-control js-editar-nombre-tarea', 'rows'=>5])->label(false) ?>
                                                                                </label>
                                                                            </div>
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
                                                            <?php
                                                                if($isColaborador || $isAbogado){
                                                            ?>
                                                                <?=Html::submitButton("<span class='ladda-label'><i class='icon wb-file' aria-hidden='true'></i>".$textoGuardar."</span>", ["data-id"=>$tarea->id_tarea, "style"=>"display:block;", "data-style"=>'zoom-in', "class"=>"btn ladda-button btn-save-texto mt-20 submit_tarea"]);?>
                                                            <?php
                                                                }
                                                            ?>
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
                                                <span>Fecha de creación: <?= Utils::changeFormatDate($tarea->fch_creacion) ?></span><br>
                                                <?php if($tarea->fch_asignacion){ ?>
                                                    <span>Fecha de asignación: <?= Utils::changeFormatDate($tarea->fch_asignacion) ?></span><br>
                                                <?php } ?> 
                                                <?php if($tarea->fch_actualizacion){ ?>
                                                    <span>Fecha de actualización: <?= Utils::changeFormatDate($tarea->fch_actualizacion) ?></span>
                                                <?php } ?>            
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


<?php
$this->registerJs('

$(document).ready(function(){

    $(".js-tarea-icon-edit").on("click", function(){
        $(".form-tarea-label").hide();
        $(".form-tarea-input").show();

        $(".form-tarea-edit").addClass("edit-tarea-visible");

        // $(this).hide();
        // $(".js-tarea-icon-save").show().css({"display": "-webkit-box", "display": "-ms-flexbox", "display": "-webkit-flex", "display": "flex"});
    });

    $(".js-tarea-icon-save").on("click", function(){
        $(".form-tarea-input").hide();
        $(".form-tarea-label").show();
        
        $(".form-tarea-edit").removeClass("edit-tarea-visible");

    });

});

', View::POS_END );

?>