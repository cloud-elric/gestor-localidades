<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use app\models\CatEstados;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ListView;
use app\assets\AppAsset;
use app\models\ConstantesWeb;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use app\models\Calendario;
// use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use app\assets\AppAssetClassicCore;
use app\models\WrkTareas;
use app\modules\ModUsuarios\models\Utils;


/* @var $this yii\web\View */
/* @var $searchModel app\models\EntLocalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localidades archivadas';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.css',
    ['depends' => [AppAsset::className()]]
  );  

$this->registerCssFile(
    '@web/webAssets/templates/classic/topbar/assets/examples/css/apps/work.css',
    ['depends' => [AppAsset::className()]]
); 

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/slidepanel/slidePanel.css',
    ['depends' => [AppAssetClassicCore::className()]]
); 

$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.js',
    ['depends' => [AppAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/js/archivadas/index.js',
    ['depends' => [AppAsset::className()]]
);

$this->registerCssFile(
    '@web/webAssets/css/localidades/index.css',
    ['depends' => [AppAsset::className()]]
);


$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.css',
    ['depends' => [AppAsset::className()]]
  ); 

  $this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/dropify/dropify.min.js',
    ['depends' => [AppAsset::className()]]
);

?>

<div class="panel-localidades-cont">
    <!-- Panel -->
    <div class="panel panel-localidades">
        <div class="panel-search">
            <h3 class="panel-search-title">Listado de localidades</h3>

            <?= $this->render('//archivadas/_search', [
                'model' => $searchModel
            ]); ?>

        </div>
    </div>

    
    <div class="panel-table">
        <?= GridView::widget([
            // 'tableOptions' => [
            //     "class" => "table"
            // ],
            
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class'=>"table table-hover"
            ],
            'layout' => '{items}{summary}{pager}',
            'columns' =>[
                
                [
                    'attribute'=>'txt_nombre',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                    'format'=>'raw',
                    'value'=>function($data){
                        $punto = 'cat-yellow';
                
                        return '<div class="panel-listado-user">
                        <a class="panel-listado-user-link no-pjax run-slide-panel" href="'.Url::base().'/archivadas/view/'.$data->id_localidad.'">' .$data->txt_nombre.'</a></div>';
                    }
                ],
                'cms',
                [
                    'label'=>'Última',
                    'format'=>'raw',
                    'value'=>function($data){
                        return 'Hoy';
                    }
                ],

                [
                    'attribute'=>'fch_asignacion',
                    'filter'=>DatePicker::widget([
                        'model'=>$searchModel,
                        'attribute'=>'fch_creacion',
                        'pickerButton'=>false,
                        'removeButton'=>false,
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]),
                    'format'=>'raw',
                    'value'=>function($data){
                        if (!$data->fch_asignacion){
                            return "(no definido)";
                        }
                        return Calendario::getDateSimple(Utils::changeFormatDateNormal($data->fch_asignacion));
                    }
                ],

                [
                    'attribute'=>'txt_arrendador',
                    'format'=>'raw'
                ],

                [
                    'label'=>'Acciones',
                    'format'=>'raw',
                    'value'=>function($data){

                        $botones =  '<div class="panel-listado-acctions-tooltip" data-toggle="tooltip" data-original-title="Detalles" data-template="<div class=\'tooltip tooltip-2 tooltip-success\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>">
                                        <a  href="'.Url::base().'/archivadas/view/'.$data->id_localidad.'"  class="btn btn-icon btn-success btn-outline panel-listado-acction acction-detail no-pjax run-slide-panel" >
                                        <i class="icon wb-eye" aria-hidden="true"></i>
                                        </a>
                                    </div>';
                        $botones .= '<div class="panel-listado-acctions-tooltip" data-toggle="tooltip" data-original-title="Tareas" data-template="<div class=\'tooltip tooltip-2 tooltip-warning\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>">
                                        <a href="'.Url::base().'/archivadas/ver-tareas-localidad?id='.$data->id_localidad.'" id="js_ver_tareas_archivadas_'.$data->txt_token.'" class="btn btn-icon btn-warning btn-outline panel-listado-acction acction-tarea no-pjax run-slide-panel"><i class="icon wb-list" aria-hidden="true"></i></a>
                                    </div>';  
                        if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){
                           // if(false){
                            $botones .= '<div class="panel-listado-acctions-tooltip">
                                            <button data-template="<div class=\'tooltip tooltip-2 tooltip-info\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>" data-url="archivadas/desarchivar-localidad?id='.$data->id_localidad.'" class="btn btn-icon btn-info btn-outline panel-listado-acction acction-archive no-pjax js_desarchivar_localidad" data-toggle="tooltip" data-original-title="Desarchivar"><i class="icon wb-inbox" aria-hidden="true"></i></button>
                                        </div>'; 
                        }   

                        return '<div class="panel-listado-acctions">'
                            //<a  href="'.Url::base().'/archivadas/view/'.$data->id_localidad.'" class="btn btn-icon btn-success btn-outline panel-listado-acction acction-detail no-pjax run-slide-panel"><i class="icon wb-eye" aria-hidden="true"></i></a>
                            //<button data-mouseDrag="false" data-url="localidades/archivar-localidad?id='.$data->id_localidad.'" id="js_archivar_localidad" class="btn btn-icon btn-info btn-outline panel-listado-acction acction-archive no-pjax" data-toggle="modal" data-target="#myModal"><i class="icon wb-inbox" aria-hidden="true"></i></button>
                            .$botones.
                        '</div>';
                    }
                    
                ]

                // [
                //     'label'=>'Acciones',
                //     'format'=>'raw',
                //     'value'=>function($data){

                //         return '<div class="panel-listado-acctions"><a class="panel-listado-acction acction-edit" href=""><i class="icon wb-plus"></i></a><a class="panel-listado-acction acction-delete" href=""><i class="icon wb-plus"></i></a></div>';
                //     }
                // ],
                
                
                
                

            ],
            'panelTemplate' => "{panelHeading}\n{items}\n{summary}\n{pager}",
            'responsive'=>true,
            'striped'=>false,
            'hover'=>false,
            'bordered'=>false,
            'pager'=>[
                'linkOptions' => [
                    'class' => 'page-link'
                ],
                'pageCssClass'=>'page-item',
                'prevPageCssClass' => 'page-item',
                'nextPageCssClass' => 'page-item',
                'firstPageCssClass' => 'page-item',
                'lastPageCssClass' => 'page-item',
                'maxButtonCount' => '5',
            ]
            
        ])
        ?>

    </div>

</div>

<?php
$this->params['modal'] = $this->render("//tareas/_modal-crear-tarea");
$this->params['modal'] .= $this->render("//localidades/_modal_motivo_archivar");
?>


