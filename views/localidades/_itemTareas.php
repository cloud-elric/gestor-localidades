<?php

use app\models\ConstantesWeb;
use yii\helpers\Html;
use app\models\WrkUsuariosTareas;
use app\modules\ModUsuarios\models\EntUsuarios;


//LISTA DE USUARIOS AGREGADOS
$idUsuarios = WrkUsuariosTareas::find()->where(['id_tarea'=>$model->id_tarea])->select('id_usuario')->asArray()->all();
$usersSeleccionados = EntUsuarios::find()->where(['in', 'id_usuario', $idUsuarios])->all();
$seleccionados = [];
$i=0;
foreach($usersSeleccionados as $userSeleccionado){
    $seleccionados[$i]['id'] = $userSeleccionado->id_usuario;
    $seleccionados[$i]['name'] = $userSeleccionado->getNombreCompleto();
    $seleccionados[$i]['avatar'] = $userSeleccionado->getImageProfile();
    $i++;
}
$seleccionados = json_encode($seleccionados);
//print_r($seleccionados);exit;
?>

<div class="panel-listado-row">
    <div class="panel-listado-col w-x"><span class="panel-listado-iden"></span></div>
    
    <div class="panel-listado-col w-m">
        <?= $model->txt_nombre ?>
    </div>
    
    <div class="panel-listado-col w-m">
        <?php
            if($model->id_tipo == 1){
                if($model->txt_path){
                    echo Html::a('Descargar', [
                        'tareas/descargar', 'id' => $model->id_tarea,
                    ]);
                }else{
                    echo "<p>No se a subido archivo</p>";
                }
            }
        ?>

        <?php
            if($model->id_tipo == 2){
                if($model->txt_tarea){
                    echo "<p>".$model->txt_tarea."</p>";
                }else{
                    echo "<p>No se a completado</p>";
                }
            }
        ?>
    </div>
    
    <div class="panel-listado-col w-m"><?= $model->txt_descripcion ?></div>
    
    
        <div id="js_div_responsables" class="panel-listado-col w-m">
            <select multiple="multiple" class="plugin-selective" data-idTar="<?= $model->id_tarea ?>" data-id="<?= $model->id_localidad ?>" data-json='<?= $seleccionados ?>'></select> 
        </div>
</div>