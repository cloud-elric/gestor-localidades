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
use app\models\Calendario;

$usuario = EntUsuarios::getUsuarioLogueado();
$isAbogado = $usuario->txt_auth_item == ConstantesWeb::ABOGADO;
$isColaborador = $usuario->txt_auth_item == ConstantesWeb::COLABORADOR;
$isDirector = $usuario->txt_auth_item == ConstantesWeb::CLIENTE;
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

<li class="list-group-item js-tarea-<?=$tarea->id_tarea?>" data-tareakey="<?=$tarea->id_tarea?>">
                                                                           
    <div class="col-sm-12 col-md-12 col-separacion js_descargar_archivo-<?=$tarea->id_tarea?>">

        <div class="tarea-fechas"> 

            <div class="tarea-actualizacion">
                <?php
                if(!$relTareaUsuario && $tarea->txt_tarea == null && $tarea->txt_path == null){
                ?>
                    <p class="borrar js_btn_eliminar_tarea js_btn_eliminar_tarea-<?= $tarea->id_tarea ?>" data-id="<?= $tarea->id_tarea ?>">Borrar</p>
                    <!-- <button class="btn btn-delete-tarea js_btn_eliminar_tarea js_btn_eliminar_tarea-<?= $tarea->id_tarea ?>" data-id="<?= $tarea->id_tarea ?>">Eliminar tarea</button> -->
                <?php
                }
                ?>

            </div>
        </div>

        <?php
        $form1 = ActiveForm::begin(['id'=>'form-tarea-nombre'.$tarea->id_tarea, 'options' => ['class' => 'tarea-actions form-tareas']]);
        ?>
            
            <?php
            if($isAbogado || $isDirector){
                $relTareaUsuario = WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->all();

            ?>
            <div class="tarea-check">
                <div class="checkbox-custom checkbox-warning">                                                    
                    <input type="checkbox" id="check-nombre" class="js-completar-tarea" data-token="<?=$tarea->id_tarea?>" name="checkbox" <?=$tarea->b_completa?"checked":""?>>
                    <label for="check-nombre" class="task-title" style="width:100%"></label>
                </div>
            </div>
            <?php
            }else{?>
            <div class="tarea-check tarea-check-completada"></div>
            <?php
            }
            ?>
            

            <?php
            if($isAbogado || $isDirector){
            ?>
                <div class="tarea-member addMember-cont">
                    <select multiple='multiple' class='plugin-selective-tareas' data-localidad="<?=$localidad->id_localidad?>" data-id='<?=$tarea->id_tarea?>' data-json='<?=$tarea->colaboradoresAsignados?>'></select> 
                </div>
            <?php
            }
            ?>
        

            <div class="form-tarea-abogado">
                <div class="form-groupes"> 
                    <?= $form1->field($tarea, 'txt_nombre', ["options" => ["class" => "form-group form-group-row"]])->textarea(['data-id'=>$tarea->id_tarea, 'class'=>'form-control form-tarea-input js-editar-nombre-tarea'])->label(false) ?>


                    <p class="form-p form-tarea-label"><?=$tarea->txt_nombre?></p>
                    <div class="form-tarea-edit">
                        <i class="icon wb-pencil icon-edit js-tarea-icon-edit" aria-hidden="true"></i>
                        <i class="icon wb-check icon-save js-tarea-icon-save" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

        <?php
        ActiveForm::end();
        ?>

        <div class="tarea-nombre">
            <?php
            if($hasArchivo){
                $arrayPath = explode("/", $tarea->txt_path);
                $count = count($arrayPath);
            ?>
                <p><?= $arrayPath[$count-1] ?></p>                                                            
                <?= Html::a(' <i class="icon fa-download" aria-hidden="true"></i>
                ', ['tareas/descargar', 'id' => $tarea->id_tarea,], ['target' => '_blank', 'class' => 'btn no-pjax btn-default btn-down-archive']);?>

            <?php
            }
            ?>
        </div>


        <?php
        if(($isColaborador || $isAbogado) && !$tarea->b_completa){
        ?> 

            <div class="tarea-fechas">
                <div class="tarea-creada">
                    <p class="item">Creada: <?= Calendario::getDateSimple(Utils::changeFormatDateNormal($tarea->fch_creacion)) ?></p>
                </div>
                <div class="tarea-actualizacion">
                        <?php if($tarea->fch_actualizacion){ ?>
                        <p class="item">Última actualización: <?= Calendario::getDateSimple(Utils::changeFormatDateNormal($tarea->fch_actualizacion)) ?></p>
                    <?php } ?> 
                    <?php
                    if(!$relTareaUsuario && $tarea->txt_tarea == null && $tarea->txt_path == null){
                    ?>
                        <p class="borrar js_btn_eliminar_tarea js_btn_eliminar_tarea-<?= $tarea->id_tarea ?>" data-id="<?= $tarea->id_tarea ?>">Borrar</p>
                    <?php } ?>                                                                
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

                    
                <div class="tarea-colaborador-actions">
                    <div class="form-tarea-colaborador-texto">
                        <div class="form-groupes"> 
                            <?= $form1->field($tarea, 'txt_tarea', ["options" => ["class" => "form-group form-group-colaborador-row"]])->textarea(['rows' => 6, 'data-id'=>$tarea->id_tarea, 'style'=>"resize:none", 'placeholder'=>"Descripción", "class"=>"form-control form-colaborador-input"])->label(false) ?>
                            <p class="form-p form-colaborador-label"><?=$tarea->txt_nombre?></p>
                            <div class="form-colaborador-edit">
                                <i class="icon wb-pencil icon-edit js-colaborador-icon-edit" aria-hidden="true"></i>
                                <i class="icon wb-check icon-save js-colaborador-icon-save" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                    
            <?php
            }
            ?>
            <?= $form->field($tarea, 'id_tipo')->hiddenInput(['class'=>'tipo-'.$tarea->id_tarea])->label(false) ?>

            <div class="form-group">
            <?php
                if($isColaborador || $isAbogado){
            ?>
                <?=Html::submitButton("<span class='ladda-label'><i class='icon wb-file' aria-hidden='true'></i>".$textoGuardar."</span>", ["data-id"=>$tarea->id_tarea, "style"=>"display:block;", "data-style"=>'zoom-in', "class"=>"btn ladda-button btn-save-texto btn-block btn-round mt-20 submit_tarea"]);?>
            <?php
                }
            ?>
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
</li>

<div class="form-groupes"> 
    <div class="form-group form-group-row field-wrktareas-txt_nombre required">

        <textarea id="wrktareas-txt_nombre" class="form-control form-tarea-input js-editar-nombre-tarea" name="WrkTareas[txt_nombre]" data-id="65" aria-required="true">lllllll llllll</textarea>

        <div class="help-block"></div>
    </div>
                
    <p class="form-p form-tarea-label">lllllll llllll</p>
    <div class="form-tarea-edit">
        <i class="icon wb-pencil icon-edit js-tarea-icon-edit" aria-hidden="true"></i>
        <i class="icon wb-check icon-save js-tarea-icon-save" aria-hidden="true"></i>
    </div>
</div>