<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CatEstados;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\web\View;
use app\models\EntEstatus;
use yii\widgets\ListView;
use app\models\ConstantesWeb;
use app\models\WrkUsuarioUsuarios;
use app\models\WrkUsuariosTareas;
use yii\helpers\Url;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = $model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.css',
    ['depends' => [AppAsset::className()]]
  ); 

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/blueimp-file-upload/jquery.fileupload.css',
    ['depends' => [AppAsset::className()]]
  );
$this->registerCssFile(
    '@web/webAssets/templates/classic/topbar/assets/examples/css/pages/project.css',
    ['depends' => [AppAsset::className()]]
  );  



$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.min.js',
    ['depends' => [AppAsset::className()]]
);


$user = Yii::$app->user->identity;
?>
<header class="slidePanel-header ryg-header">
  <div class="slidePanel-actions" aria-label="actions" role="group">
    <button type="button" class="btn btn-pure btn-inverse slidePanel-close actions-top icon wb-close"
      aria-hidden="true"></button>
  </div>
  <h1>A Detalles de la localidad: <?=$model->txt_nombre?></h1>
</header>

    <div class="page ryg-page">
        <div class="page-content">
            <div class="ent-localidades-view">
                <div class="ent-localidades-view-head">
                    <?php //if(Yii::$app->user->identity->txt_auth_item == "abogado"){ ?>
                        <?= Html::a('<i class="icon wb-pencil" aria-hidden="true"></i> Editar', ['update', 'id' => $model->id_localidad], ['class' => 'btn btn-update no-pjax']) ?>
                        <?php # Html::a('<i class="icon wb-trash" aria-hidden="true"></i>', ['delete', 'id' => $model->id_localidad], [
                            #'class' => 'btn btn-delete',
                            #'data' => [
                             #   'confirm' => 'Are you sure you want to delete this item?',
                              #  'method' => 'post',
                            #],
                        #]) ?>
                    <?php //} ?>
                </div>

                <div class="ent-localidades-view-body">

                    <div class="ent-localidades-view-panel">
                        

                        <div class="row">
                            <div class="col-md-12 col">

                             
                                
                                <div class="ent-localidades-view-panel-int">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Estado: 
                                            <?php
                                            
                                            $estado = CatEstados::find()->where(['id_estado'=>$model->id_estado])->one();
                                            echo $estado->txt_nombre;
                                            
                                            ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Nombre: 
                                            <?= $model->txt_nombre ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Arrendador: 
                                            <?= $model->txt_arrendador ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Beneficiario: 
                                            <?= $model->txt_beneficiario ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Calle: 
                                            <?= $model->txt_calle ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Colonia: 
                                            <?= $model->txt_colonia ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Municipio: 
                                            <?= $model->txt_municipio ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Codigo postal: 
                                            <?= $model->txt_cp ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Estatus: 
                                            <?php
                                            $estatus = EntEstatus::find()->where(['id_localidad'=>$model->id_localidad])->orderBy('fch_creacion')->all();
                                            $arr = "";
                                            foreach ($estatus as $est){
                                                $arr .= '<span class="badge badge-outline badge-success badge-round mr-10">'.$est->txt_estatus.'</span>';
                                            }
                                            echo $arr;
                                            ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Antecedentes: 
                                            <?= $model->txt_antecedentes ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Num Renta Actual: 
                                            <?= $model->num_renta_actual ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Num Incremento Autotizado: 
                                            <?= $model->num_incremento_autorizado ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Fecha Vencimiento Contratado: 
                                            <?= Utils::changeFormatDate($model->fch_vencimiento_contratro); ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Fecha Creación: 
                                            <?= Utils::changeFormatDate($model->fch_creacion); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            Fecha Asignación: 
                                            <?= Utils::changeFormatDate($model->fch_asignacion); ?>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            Problemas Acceso: 
                                            <?php
                                            if($model->b_problemas_acceso == 0){
                                                echo "No";
                                            }else{
                                                echo "Si";
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
        </div>    
    </div>