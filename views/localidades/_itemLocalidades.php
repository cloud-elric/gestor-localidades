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

$userRel = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$model->id_localidad])->all();
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
    
    <?php 
    if($userRel){ 
    ?>
        <div id="js_div_responsables" class="panel-listado-col w-m">
            <select multiple="multiple" class="plugin-selective"></select>
    <?php    
        foreach($userRel as $userR){
            $usuario = EntUsuarios::find()->where(['id_usuario'=>$userR->id_usuario])->one();
    ?>
            <!--<img class="panel-listado-img" src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt="<?= $usuario->txt_username ?>">-->
    <?php
        }
    ?>
        </div>
    <?php
    }else{
    ?>
        <div class="panel-listado-col w-m"><img class="panel-listado-img" src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt="<?= $user->txt_username ?>"></div>
    <?php
        }
    ?>
    
    <div class="panel-listado-col w-s"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>
    
    <?php 
    $grupoTrabajo = WrkUsuarioUsuarios::find()->where(['id_usuario_padre'=>$user->id_usuario])->select('id_usuario_hijo')->asArray();
    if($user->txt_auth_item == ConstantesWeb::ABOGADO){ 
    ?>
        <?= Html::activeDropDownList($model, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
            /*->where(['!=', 'txt_auth_item', 'super-admin'])
            ->andWhere(['!=', 'txt_auth_item', 'abogado'])*/
            ->where(['in', 'id_usuario', $grupoTrabajo])
            ->andWhere(['id_status'=>2])
            ->orderBy('txt_username')
            ->asArray()
            ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$model->id_localidad, 'class' => 'select select-'.$model->id_localidad, 'data-idLoc' => $model->id_localidad, 'prompt' => 'Seleccionar cliente'])
        ?>
    <?php }else if($user->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
        <?= Html::activeDropDownList($model, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
            /*->where(['txt_auth_item'=>'usuario-cliente'])
            ->andWhere(['!=', 'txt_auth_item', 'abogado'])*/
            ->where(['in', 'id_usuario', $grupoTrabajo])
            ->andWhere(['id_status'=>2])
            ->orderBy('txt_username')
            ->asArray()
            ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$model->id_localidad, 'class' => 'select select-'.$model->id_localidad, 'data-idLoc' => $model->id_localidad, 'prompt' => 'Seleccionar cliente'])
        ?>
    <?php }else{} ?>
</div>
