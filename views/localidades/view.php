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
use app\models\Calendario;

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
                    <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                        <?= Html::a('<i class="icon wb-pencil" aria-hidden="true"></i> Editar', ['update', 'id' => $model->id_localidad], ['class' => 'btn btn-update no-pjax']) ?>
                    <?php } ?>
                    <?php # Html::a('<i class="icon wb-trash" aria-hidden="true"></i>', ['delete', 'id' => $model->id_localidad], [
                        #'class' => 'btn btn-delete',
                        #'data' => [
                            #   'confirm' => 'Are you sure you want to delete this item?',
                            #  'method' => 'post',
                        #],
                    #]) ?>
                </div>

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

                                        <!-- <div class="col-sm-12 col-md-12">
                                            <form action="" class="form-detalle-localidad">
                                                <h6>Datos</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control form-input" placeholder="Algo">
                                                    <p class="form-p form-label">Algo de lorem ipsum</p>
                                                    <div class="form-edit">
                                                        <i class="icon wb-pencil icon-edit js-icon-edit" aria-hidden="true"></i>
                                                        <i class="icon wb-check icon-save js-icon-save" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            
                                        </div> -->

                                        <div class="col-sm-12 col-md-12 ">
                                            <form action="" class="form-detalle-localidad">
                                                <span>Estatus: </span>
                                                <?php
                                                $estatus = EntEstatus::find()->where(['id_localidad'=>$model->id_localidad])->orderBy('fch_creacion')->all();
                                                $arr = "";
                                                foreach ($estatus as $est){
                                                    if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){                                                    
                                                ?>
                                                        <div class="form-group">
                                                                <textarea class="form-control form-input form-input-<?=$est->id_estatus?>" placeholder="Estatus"><?=$est->txt_estatus?></textarea>
                                                            <?php
                                                                echo '<p class="form-p form-label form-label-'.$est->id_estatus.'"><span class="badge badge-outline badge-success js-span-texto-'.$est->id_estatus.' badge-round ml-5 vertical-align-middle">'.$est->txt_estatus.'</span></p>';
                                                            ?>
                                                            <div class="form-edit form-edit-<?=$est->id_estatus?>">
                                                                <i class="icon wb-pencil icon-edit js-icon-edit" data-id="<?=$est->id_estatus?>" aria-hidden="true"></i>
                                                                <i class="icon wb-check icon-save js-icon-save" data-id="<?=$est->id_estatus?>" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    <?php 
                                                    }else{ 
                                                    ?>
                                                        <div class="form-group">
                                                            <p class="form-p form-label"><span class="badge badge-outline badge-success badge-round ml-5 vertical-align-middle"><?=$est->txt_estatus?></span></p>
                                                        </div>
                                                <?php 
                                                    }
                                                } ?>
                                            </form>
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
                                            <p><?= Calendario::getDateSimple(Utils::changeFormatDateNormal($model->fch_vencimiento_contratro)); ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Fecha Creación: </span>
                                            <p><?= Calendario::getDateSimple(Utils::changeFormatDateNormal($model->fch_creacion)); ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <span>Fecha Asignación: </span>
                                            <p><?= Calendario::getDateSimple(Utils::changeFormatDateNormal($model->fch_asignacion)); ?></p>
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

<?php
$this->registerJs('

$(document).ready(function(){

    $(".js-icon-edit").on("click", function(){
        var id = $(this).data("id");
        $(".form-label-"+id).hide();
        $(".form-input-"+id).show();

        $(".form-edit-"+id).addClass("edit-visible");

        // $(this).hide();
        // $(".js-icon-save").show().css({"display": "-webkit-box", "display": "-ms-flexbox", "display": "-webkit-flex", "display": "flex"});
    });

    $(".js-icon-save").on("click", function(){
        var id = $(this).data("id");
        var texto = $(".form-input-"+id).val();
        
        $.ajax({
            url: baseUrl+"localidades/editar-estatus?id="+id,
            data: {txt_estatus: texto},
            type: "POST",
            success: function(resp){
                if(resp.status == "success"){
                    $(".form-input-"+id).hide();
                    $(".js-span-texto-"+id).text(texto);
                    $(".form-label-"+id).show();
                    $(".form-edit-"+id).removeClass("edit-visible");
                }
            }    
        });
    });
});

', View::POS_END );

?>