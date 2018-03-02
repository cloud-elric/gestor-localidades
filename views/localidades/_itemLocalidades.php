<?php
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\ConstantesWeb;

$utils = new Utils();
$user = Yii::$app->user->identity;
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
    <div class="panel-listado-col w-m"><img class="panel-listado-img" src="<?=EntUsuarios::getUsuarioLogueado()->imageProfile?>" alt=""></div>
    <div class="panel-listado-col w-s"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>
    <?php if($user->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
        <?= Html::activeDropDownList($model, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
            ->where(['!=', 'txt_auth_item', 'super-admin'])
            ->andWhere(['!=', 'txt_auth_item', 'abogado'])
            /*->where(['id_usuario'=>$data->id_usuario])*/
            ->andWhere(['id_status'=>2])
            ->orderBy('txt_username')
            ->asArray()
            ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$model->id_localidad, 'class' => 'select select-'.$model->id_localidad, 'data-idLoc' => $model->id_localidad, 'prompt' => 'Seleccionar cliente'])
        ?>
    <?php }else if($user->txt_auth_item == ConstantesWeb::CLIENTE){ ?>
        <?= Html::activeDropDownList($model, 'id_usuario', ArrayHelper::map(EntUsuarios::find()
            ->where(['txt_auth_item'=>'usuario-cliente'])
            /*->andWhere(['!=', 'txt_auth_item', 'abogado'])
            ->where(['id_usuario'=>$data->id_usuario])*/
            ->andWhere(['id_status'=>2])
            ->orderBy('txt_username')
            ->asArray()
            ->all(), 'id_usuario', 'txt_username'),['id' => "localidad-".$model->id_localidad, 'class' => 'select select-'.$model->id_localidad, 'data-idLoc' => $model->id_localidad, 'prompt' => 'Seleccionar cliente'])
        ?>
    <?php }else{} ?>
</div>
