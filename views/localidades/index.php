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


/* @var $this yii\web\View */
/* @var $searchModel app\models\EntLocalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localidades';
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
    '@web/webAssets/js/localidades/index.js',
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

            <?= $this->render('_search', [
                'model' => $searchModel,
                //'estatus' => $estatus            
            ]); ?>
        </div>
    </div>

    
    <div class="panel-table">
        <?= GridView::widget([
            // 'tableOptions' => [
            //     "class" => "table"
            // ],
            'pjax'=>true,
            'pjaxSettings'=>[
                'options'=>[
                    'linkSelector'=>"a:not(.no-pjax)",
                    'id'=>'pjax-usuarios'
                ]
                ],
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class'=>"table table-hover"
            ],
            'layout' => '{items}{summary}{pager}',
            'columns' =>[
                [
                    'attribute'=>'cms',
                    'format'=>'raw',
                    'value'=>function($data){

                        $hoy = time();//date("Y-m-d");
                        $fch_creacion = strtotime($data->fch_creacion);
                        $punto = 'cat-green';
                        
                        $tareas = $data->wrkTareas;
                        if($tareas){
                            foreach($tareas as $tarea){
                                $fch_creacion = strtotime($tarea->fch_creacion);
                                $res = $hoy - $fch_creacion;
                                $res1 = round($res / (60*60*24));
                                
                                if($res1 > ConstantesWeb::DIAS_RESTANTES && (!$tarea->txt_tarea && !$tarea->txt_path) && $tarea->b_completa == 0){
                                    $punto = 'cat-red';
                                    break;
                                }
                                // //if((!$tarea->txt_tarea && $tarea->id_tipo == ConstantesWeb::TAREA_ABIERTO) || (!$tarea->txt_path && $tarea->id_tipo == ConstantesWeb::TAREA_ARCHIVO)){
                                if(($tarea->txt_tarea || $tarea->txt_path) && $tarea->b_completa == 0){
                                    $punto = 'cat-yellow';
                                    //break;
                                }
                                if($res1 < ConstantesWeb::DIAS_RESTANTES && (!$tarea->txt_tarea && !$tarea->txt_path) && $tarea->b_completa == 0){
                                    $punto = 'cat-yellow';
                                }
                            }
                        }else{
                            $punto = 'cat-yellow';
                        }

                        if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::SUPER_ADMIN){
                            return '<div class="panel-listado-user"><div class="panel-listado-user-cats"><span class="panel-listado-user-cat '.$punto.'"></span></div>' .$data->cms.'</div>';
                        }
                        return '<div class="panel-listado-user"><div class="panel-listado-user-cats"><span class="panel-listado-user-cat '.$punto.'"></span></div>' .$data->cms.'</div>';
                    }
                ],
                [
                    'attribute'=>'txt_nombre',
                    'headerOptions' => [
                        'class' => 'pl-10'
                    ],
                    'format'=>'raw',
                    'value'=>function($data){

                        return $data->txt_nombre;
                        $hoy = time();//date("Y-m-d");
                        $fch_creacion = strtotime($data->fch_creacion);
                        $punto = 'cat-yellow';
                        
                        $tareas = $data->wrkTareas;
                        if($tareas){
                            foreach($tareas as $tarea){
                                $fch_creacion = strtotime($tarea->fch_creacion);
                                $res = $hoy - $fch_creacion;
                                $res1 = round($res / (60*60*24));
                                
                                if($res1 > ConstantesWeb::DIAS_RESTANTES && $tarea->b_completa == 0){
                                    $punto = 'cat-red';
                                    break;
                                }
                                if((!$tarea->txt_tarea && $tarea->id_tipo == ConstantesWeb::TAREA_ABIERTO) || (!$tarea->txt_path && $tarea->id_tipo == ConstantesWeb::TAREA_ARCHIVO)){

                                        $punto = 'cat-yellow';
                                        break;                                    
                                    
                                }
                                if($tarea->txt_tarea || $tarea->txt_path){
                                    $punto = 'cat-green';
                                    break;
                                }
                            }
                        }
                        if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::SUPER_ADMIN){
                            return '<div class="panel-listado-user"><div class="panel-listado-user-cats"><span class="panel-listado-user-cat '.$punto.'"></span></div>' .$data->txt_nombre.'</div>';
                        }
                        return '<div class="panel-listado-user"><div class="panel-listado-user-cats"><span class="panel-listado-user-cat '.$punto.'"></span></div>
                        <a  class="panel-listado-user-link no-pjax run-slide-panel" href="'.Url::base().'/localidades/view/'.$data->id_localidad.'">' .$data->txt_nombre.'</a></div>';
                    }
                ],
                
                [
                    'attribute'=>'fch_asignacion',
                    'contentOptions' => [
                        'class'=>"td-fecha"
                    ],
                    'label' => 'Fecha de Asignación',
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
                        return Calendario::getDateSimple($data->fch_asignacion);
                    }
                ],

                [
                    'attribute'=>'txt_arrendador',
                    'format'=>'raw'
                ],
                [
                    'label'=>'Responsable',
                    'format'=>'raw',
                    'value'=>function($data){

                        //LISTA DE USUARIOS AGREGADOS
                        $usuariosSeleccionados = $data->usuarios;
                        $seleccionados = [];
                        $usuarioDefault = new EntUsuarios();
                        $i=0;
                        foreach($usuariosSeleccionados as $usuarioSeleccionado){
                            $usuarioDefault = $usuarioSeleccionado;
                            $seleccionados[$i]['id'] = $usuarioSeleccionado->id_usuario;
                            $seleccionados[$i]['name'] = $usuarioSeleccionado->getNombreCompleto();
                            $seleccionados[$i]['avatar'] = $usuarioSeleccionado->getImageProfile();
                            $i++;
                        }
                        $seleccionados = json_encode($seleccionados);
                        
                            if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){
                                return "<div id='js_div_responsables' class='panel-listado-col w-m'>
                                    <select multiple='multiple' class='plugin-selective' data-id='".$data->id_localidad ."' data-json='". $seleccionados ."'></select> 
                                </div>";
                            }
                                
                            
                            return '
                            <ul class="addMember-items">
                                <li class="addMember-item">
                                    <img class="avatar tooltip-success" src="'.$usuarioDefault->imageProfile.'" data-toggle="tooltip" data-original-title="'.$usuarioDefault->nombreCompleto.'"">
                                </li>
                            </ul>
                            ';
                    }
                ],

                [
                    'label'=>'Acciones',
                    'contentOptions' => [
                        'class'=>"td-actions"
                    ],
                    'format'=>'raw',
                    'value'=>function($data){
                        $botones = '';
                        if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){

                        $botones =  '<div class="panel-listado-acctions-tooltip" data-toggle="tooltip" data-original-title="Detalles" data-template="<div class=\'tooltip tooltip-2 tooltip-success\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>">
                                        <a  href="'.Url::base().'/localidades/view/'.$data->id_localidad.'"  class="btn btn-icon btn-success btn-outline panel-listado-acction acction-detail no-pjax run-slide-panel" >
                                        <i class="icon ion-md-list" aria-hidden="true"></i>
                                        </a>
                                    </div>';
                        $botones .= '<div class="panel-listado-acctions-tooltip" data-toggle="tooltip" data-original-title="Tareas" data-template="<div class=\'tooltip tooltip-2 tooltip-warning\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>">
                                        <a href="'.Url::base().'/localidades/ver-tareas-localidad?id='.$data->id_localidad.'" id="js_ver_localidades_'.$data->txt_token.'" class="btn btn-icon btn-warning btn-outline panel-listado-acction acction-tarea no-pjax run-slide-panel"><i class="icon ion-md-hand" aria-hidden="true"></i></a>
                                    </div>';  
                        
                           // if(false){
                            $botones .= '<div class="panel-listado-acctions-tooltip">
                                            <button data-template="<div class=\'tooltip tooltip-2 tooltip-info\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>" data-url="localidades/archivar-localidad?id='.$data->id_localidad.'" class="btn btn-icon btn-info btn-outline panel-listado-acction acction-archive no-pjax js_archivar_localidad" data-toggle="tooltip" data-original-title="Archivar"><i class="icon ion-md-archive" aria-hidden="true"></i></button>
                                        </div>'; 
                        }
                        
                        if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::COLABORADOR){
                            $botones =  '<div class="panel-listado-acctions-tooltip" data-toggle="tooltip" data-original-title="Detalles" data-template="<div class=\'tooltip tooltip-2 tooltip-success\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>">
                                <a  href="'.Url::base().'/localidades/view/'.$data->id_localidad.'"  class="btn btn-icon btn-success btn-outline panel-listado-acction acction-detail no-pjax run-slide-panel" >
                                <i class="icon ion-md-list" aria-hidden="true"></i>
                                </a>
                            </div>';
                            $botones .= '<div class="panel-listado-acctions-tooltip" data-toggle="tooltip" data-original-title="Tareas" data-template="<div class=\'tooltip tooltip-2 tooltip-warning\' role=\'tooltip\'><div class=\'arrow\'></div><div class=\'tooltip-inner\'></div></div>">
                                <a href="'.Url::base().'/localidades/ver-tareas-localidad?id='.$data->id_localidad.'" id="js_ver_localidades_'.$data->txt_token.'" class="btn btn-icon btn-warning btn-outline panel-listado-acction acction-tarea no-pjax run-slide-panel"><i class="icon ion-md-hand" aria-hidden="true"></i></a>
                            </div>'; 
                        }
                        
                        return '<div class="panel-listado-acctions">
                                    '.$botones.'
                                </div>
                        ';
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

<?php
$this->registerJs("

$(document).ready(function(){
    var member = ".$jsonAgregar.";

    $('.plugin-selective').each(function () {
        var elemento = $(this);
        //console.log(elemento.data('json'));
        elemento.selective({
          closeOnSelect: true , 
          namespace: 'addMember',
          selected: elemento.data('json'),
          local: member,
          onAfterSelected: function(e){
              //alert(elemento.val());
          },
          onAfterItemAdd: function(e){
            var idLoc = elemento.data('id');
            $('*[data-key=\"'+idLoc+'\"] .addMember-trigger-button').hide();

            var idUser = elemento.val();

            $.ajax({
                url: '".Yii::$app->urlManager->createAbsoluteUrl(['localidades/asignar-usuarios'])."',
                data: {idL: idLoc, idU: idUser},
                dataType: 'json',
                type: 'POST',
                success: function(resp){
                    if(resp.status == 'success'){
                        console.log('Asignacion correcta');
                        // swal('Good job!', 'You clicked the button!', 'success');
                        // Display a success toast, with a title
                        showToastr('Se asigno al director jurídico y se le ha enviado una notificación', 'success');
                    }
                }
            });
          },
          onAfterItemRemove: function(e){
            var idLoc = elemento.data('id');
            $('*[data-key=\"'+idLoc+'\"] .addMember-trigger-button').show();
            var idUser = elemento.val();
            if(!idUser){
                idUser = -1;
            }

            $.ajax({
                url: '".Yii::$app->urlManager->createAbsoluteUrl(['localidades/asignar-usuarios-eliminar'])."',
                data: {idL: idLoc, idU: idUser},
                dataType: 'json',
                type: 'POST',
                success: function(resp){
                    if(resp.status == 'success'){
                        console.log('Eliminacion correcta');
                        //wal('Deleted!', 'Your imaginary file has been deleted.', 'success');
                        // Display a success toast, with a title
                        showToastr('Se removio la asignación del director jurídico', 'success');
                        
                    }
                }
            });
          },
          buildFromHtml: false,
          tpl: {
            optionValue: function optionValue(data) {
              return data.id;
            },
            frame: function frame() {
              return '<div class=\"' + this.namespace + '\">                ' + this.options.tpl.items.call(this) + '                <div class=\"' + this.namespace + '-trigger\">                ' + this.options.tpl.triggerButton.call(this) + '                <div class=\"' + this.namespace + '-trigger-dropdown\">                ' + this.options.tpl.list.call(this) + '                </div>                </div>                </div>';
        
              // i++;
            },
            triggerButton: function triggerButton() {
                var isAsignado = 'block';
                
                if(elemento.data('json').length>0){
                    var isAsignado = 'none';
                }
              return '<div style=\"display:'+isAsignado+'\" class=\"' + this.namespace + '-trigger-button\"><i class=\"wb-plus\"></i></div>';
            },
            listItem: function listItem(data) {
              return '<li class=\"' + this.namespace + '-list-item\"><img  class=\"avatar\" src=\"' + data.avatar + '\">' + data.name + '</li>';
            },
            item: function item(data) {
              return '<li class=\"' + this.namespace + '-item\"><img data-toggle=\"tooltip\" data-original-title=\"' + data.name + '\" class=\"avatar\" src=\"' + data.avatar + '\" >' + this.options.tpl.itemRemove.call(this) + '</li>';
            },
            itemRemove: function itemRemove() {
              return '<span class=\"' + this.namespace + '-remove\"><i class=\"wb-minus-circle\"></i></span>';
            },
            option: function option(data) {
              return '<option value=\"' + this.options.tpl.optionValue.call(this, data) + '\">' + data.name + '</option>';
            }
          }
        });
    });
});

", View::POS_END );

?>