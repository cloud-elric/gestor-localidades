<?php

use yii\helpers\Html;
// use yii\grid\GridView;
// use yii\widgets\ListView;
use app\models\Calendario;
use kartik\grid\GridView;
use app\modules\ModUsuarios\models\EntUsuarios;
use kop\y2sp\ScrollPager;
use app\components\LinkSorterExtends;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = [
  'label' => '<i class="icon pe-users"></i>'.$this->title, 
  'encode' => false,
  'template'=>'<li class="breadcrumb-item">{link}</li>', 
];

$this->params['headerActions'] = '<a class="btn btn-primary no-pjax" href="'.Url::base().'/usuarios/create"><i class="icon wb-plus"></i> Agregar usuario</a>';

$this->params['classBody'] = "site-navbar-small page-user ryg-body";

$this->registerCssFile(
  '@web/webAssets/templates/classic/topbar/assets/examples/css/pages/user.css',
  ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerJsFile(
  '@web/webAssets/templates/classic/global/js/Plugin/responsive-tabs.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerJsFile(
  '@web/webAssets/templates/classic/global/js/Plugin/tabs.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);

$this->registerJsFile(
  '@web/webAssets/js/usuarios/index.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);


?>
  
    
<!-- Panel -->
<div class="panel panel-list-user-table">
  
    <?php
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'=>$searchModel,
        'options' => [
          'class'=>"panel-table-int"
        ],
        'responsive'=>true,
        'striped'=>false,
        'hover'=>false,
        'bordered'=>false,
        'pjax'=>true,
        'pjaxSettings'=>[
          'options'=>[
            'linkSelector'=>"a:not(.no-pjax)",
            //'timeout' => 20000000000, 
          ]
        ],
        'tableOptions' => [
          'class'=>"table table-hover"
        ],
        'layout' => '{items}{summary}{pager}',
        'columns' =>[
          [
            'attribute' => 'nombreCompleto',
            
            'format'=>'raw',
            'contentOptions' => [
              'class'=>"flex"
            ],
            'value'=>function($data){
                
              return '<a class="no-pjax" href="'.Url::base().'/usuarios/update/'.$data->id_usuario.'"><img class="panel-listado-img" src="'.$data->imageProfile.'" alt="">
              <span>'.$data->nombreCompleto .'</span></a>';
            }
          ],
           [
             'attribute' => 'roleDescription',
             'filter'=>ArrayHelper::map($roles, 'name', 'description'),
             'filterInputOptions'=>[
               "prompt"=>"Todos",
               'class'=>'form-control'
             ]
           ],
           'txt_email:raw',
          
          [
            'attribute' => 'fch_creacion',
            'label' => 'Fecha de Creación',
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
                
              return Calendario::getDateSimple($data->fch_creacion);
            }
          ],
          [
            'attribute' => 'id_status',
            'filter'=>[EntUsuarios::STATUS_ACTIVED=>'Activo', EntUsuarios::STATUS_BLOCKED=>'Inactivo'],
            'filterInputOptions'=>[
              "prompt"=>"Todos",
              'class'=>'form-control'
            ],
            'format'=>'raw',
            
            'value'=>function($data){

            $activo = $data->id_status == 2?'active':'';
            $inactivo = $data->id_status == 1||$data->id_status == 3?'active':'';
                
              return '<div class="btn-group" data-toggle="buttons" role="group">
              <label class="btn btn-active '.$activo.'"  data-token="'.$data->txt_token.'">
              <input class="js-activar-usuario" type="radio" name="options" autocomplete="off" value="male" checked />
              Activo
              </label>
              <label class="btn btn-inactive '.$inactivo.'" data-token="'.$data->txt_token.'">
              <input class="js-bloquear-usuario"  type="radio" name="options" autocomplete="off" value="female" />
              Inactivo
              </label>
              </div>';
            }

          ],
          [
            'attribute' => 'Acciones',
            'format'=>'raw',
           
            'value'=>function($data){
             
               $botonEditar =  '<a data-template="<div class=\'tooltip tooltip-success\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>" 
                      data-toggle="tooltip" data-original-title="Editar" href="'.Url::base().'/usuarios/update/'.$data->id_usuario.'" class="btn btn-icon btn-success btn-outline panel-listado-acction acction-edit no-pjax"><i class="icon ion-md-create" aria-hidden="true"></i></a>';
               $botonEnviarBienvenida = '<button data-template="<div class=\'tooltip tooltip-warning\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>" 
                      data-toggle="tooltip" data-original-title="Enviar contraseña" data-style="zoom-in" data-token="'.$data->txt_token.'" class="btn btn-icon btn-warning btn-outline ladda-button panel-listado-acction acction-mail js-reenviar-email"><i class="icon ion-md-mail" aria-hidden="true"></i></button>';
              // return $botonEditar.$botonEnviarBienvenida;

              return '<div class="panel-listado-acctions">'.$botonEditar.$botonEnviarBienvenida.'</div>';

            }
          ]
        ],
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
      
    ]);?>
    

</div>
<!-- End Panel -->
