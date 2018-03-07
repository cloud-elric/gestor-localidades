<?php
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\ConstantesWeb;
use app\models\WrkUsuarioUsuarios;
use app\models\WrkUsuariosLocalidades;

$utils = new Utils();
$user = Yii::$app->user->identity;

//LISTA DE USUARIOS AGREGADOS
$usuariosSeleccionados = $model->usuarios;
$seleccionados = [];
$i=0;
foreach($usuariosSeleccionados as $usuarioSeleccionado){
    $seleccionados[$i]['id'] = $usuarioSeleccionado->id_usuario;
    $seleccionados[$i]['name'] = $usuarioSeleccionado->getNombreCompleto();
    $seleccionados[$i]['avatar'] = $usuarioSeleccionado->getImageProfile();
    $i++;
}
$seleccionados = json_encode($seleccionados);
//print_r($seleccionados);exit;
?>
    
<div class="panel-listado-row">
    <div class="panel-listado-col w-x"><span class="panel-listado-iden"></span></div>
    <div class="panel-listado-col w-m">
        <?= Html::a($model->txt_nombre, [
            'localidades/view', 'id' => $model->id_localidad
        ]) ?>
    </div>
    <div class="panel-listado-col w-m">Hoy</div>

    <div class="panel-listado-col w-m"><?= $utils::changeFormatDate($model->fch_asignacion) ?></div>
    
    <div class="panel-listado-col w-m"><?= $model->txt_arrendador ?></div>
    
    <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
        <div id="js_div_responsables" class="panel-listado-col w-m">
            <select multiple="multiple" class="plugin-selective" data-id="<?= $model->id_localidad ?>" data-json='<?= $seleccionados ?>'></select> 
        </div>
    <?php } ?>
    
    <div class="panel-listado-col w-s"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>
    
    <?php 
    /**$grupoTrabajo = WrkUsuarioUsuarios::find()->where(['id_usuario_padre'=>$user->id_usuario])->select('id_usuario_hijo')->asArray();
    if($user->txt_auth_item == ConstantesWeb::ABOGADO){ 
    ?>
        <?= Html::activeDropDownList($model, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
            /*->where(['!=', 'txt_auth_item', 'super-admin'])
            ->andWhere(['!=', 'txt_auth_item', 'abogado'])*/
           /* ->where(['in', 'id_usuario', $grupoTrabajo])
            ->andWhere(['id_status'=>2])
            ->orderBy('txt_username')
            ->asArray()
            ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$model->id_localidad, 'class' => 'select select-'.$model->id_localidad, 'data-idLoc' => $model->id_localidad, 'prompt' => 'Seleccionar cliente'])
        ?>
    <?php }else if($user->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
        <?= Html::activeDropDownList($model, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
            /*->where(['txt_auth_item'=>'usuario-cliente'])
            ->andWhere(['!=', 'txt_auth_item', 'abogado'])*/
            /*->where(['in', 'id_usuario', $grupoTrabajo])
            ->andWhere(['id_status'=>2])
            ->orderBy('txt_username')
            ->asArray()
            ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$model->id_localidad, 'class' => 'select select-'.$model->id_localidad, 'data-idLoc' => $model->id_localidad, 'prompt' => 'Seleccionar cliente'])
        ?>
    <?php }else{} */?>
</div>
