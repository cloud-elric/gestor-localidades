<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CatEstados;
use app\models\CatColonias;
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
  <h1>Detalles de la localidad: <?=$model->txt_nombre?></h1>
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
                                        <div class="col-sm-12 col-md-12">
                                            <span>Nombre localidad: </span>
                                            <p><?= $model->txt_nombre ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Arrendador: </span>
                                            <p><?= $model->txt_arrendador ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Beneficiario: </span>
                                            <p><?= $model->txt_beneficiario ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Cms: </span>
                                            <p><?= $model->cms ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Contacto: </span>
                                            <p><?= $model->txt_contacto ?></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <span>Calle: </span>
                                            <p><?= $model->txt_calle ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Colonia: </span>
                                            <?php
                                            if($model->txt_colonia){
                                                $colonia = CatColonias::find()->where(['id_colonia'=>$model->txt_colonia])->one();
                                            ?>
                                                <p><?= $colonia->txt_nombre ?></p>
                                            <?php
                                            }else{
                                            ?>
                                                <p><?= $model->texto_colonia ?></p>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Municipio: </span>
                                            <p><?= $model->txt_municipio ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Código postal: </span>
                                            <p><?= $model->txt_cp ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Estado: </span>
                                            <p>
                                                <?php
                                                if($model->id_estado){
                                                    $estado = CatEstados::find()->where(['id_estado'=>$model->id_estado])->one();
                                                    echo $estado->txt_nombre;
                                                ?>

                                                <?php
                                                }else{
                                                ?>
                                                    <?= $model->texto_estado ?>
                                                <?php
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <span>Estatus: </span>
                                            <p>
                                            <?php
                                            $estatus = EntEstatus::find()->where(['id_localidad'=>$model->id_localidad])->orderBy('fch_creacion')->all();
                                            $arr = "";
                                            foreach ($estatus as $est){
                                                $arr .= '<span class="badge badge-outline badge-success badge-round ml-5 vertical-align-middle">'.$est->txt_estatus.'</span>';
                                            }
                                            echo "<p>".$arr."</p>";
                                            ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Antecedentes: </span>
                                            <p><?= $model->txt_antecedentes ?></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <span>Tipo de moneda: </span>
                                            <?php
                                            $moneda = $model->moneda;
                                            echo "<p>".$moneda->txt_moneda."</p>";
                                            ?>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Frecuencia de pago: </span>
                                            <p><?= $model->txt_frecuencia ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Renta Actual: </span>
                                            <p><?= $model->num_renta_actual ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Porcentaje de incremento preautorizado: </span>
                                            <p><?= $model->num_incremento_autorizado ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <?php if($model->num_pretencion_renta){ ?>
                                                <span>Renta pre-autorizada: </span>
                                                <p><?= $model->num_pretencion_renta ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <?php if($model->num_incremento_cliente){ ?>
                                                <span>Porcentaje de incremento solicitado por arrendador: </span>
                                                <p><?= $model->num_incremento_cliente ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <?php if($model->num_pretencion_renta_cliente){ ?>
                                                <span>Pretensión de renta del arrendador: </span>
                                                <p><?= $model->num_pretencion_renta_cliente ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Fecha Vencimiento Contrato: </span>
                                            <p><?= Utils::changeFormatDate($model->fch_vencimiento_contratro); ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Fecha Creación: </span>
                                            <p><?= Utils::changeFormatDate($model->fch_creacion); ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Fecha Asignación: </span>
                                            <p><?= Utils::changeFormatDate($model->fch_asignacion); ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Problemas Acceso: </span>
                                            <p>
                                            <?php
                                            if($model->b_problemas_acceso == 0){
                                                echo "No";
                                            }else{
                                                echo "Si";
                                            }
                                            ?>
                                            </p>
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
