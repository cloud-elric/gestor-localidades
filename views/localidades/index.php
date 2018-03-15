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


/* @var $this yii\web\View */
/* @var $searchModel app\models\EntLocalidadesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localidades';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.css',
    ['depends' => [AppAsset::className()]]
  );  
  
$this->registerJsFile(
    '@web/webAssets/templates/classic/global/vendor/jquery-selective/jquery-selective.min.js',
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

            <div class="row mt-30">
                <div class="col-md-3 offset-9">
                
                    <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                        <?= Html::a('<i class="icon wb-plus"></i> Crear Localidades', ['create'], ['class' => 'btn btn-add']) ?>
                    <?php } ?>

                </div>

            </div>

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
                    'attribute'=>'txt_nombre',
                    'headerOptions' => [
                        'class' => 'text-center'
                    ],
                    'format'=>'raw',
                    'value'=>function($data){
                        return '<div class="panel-listado-user"><div class="panel-listado-user-cats"><span class="panel-listado-user-cat cat-yellow"></span></div><a class="panel-listado-user-link" href="'.Url::base().'/localidades/view/'.$data->id_localidad.'">' .$data->txt_nombre.'</a></div>';
                    }
                ],

                [
                    'label'=>'Ultima',
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
                        $i=0;
                        foreach($usuariosSeleccionados as $usuarioSeleccionado){
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
                    }
                ],

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
$this->registerJs("

$(document).ready(function(){
    var member = ".$jsonAgregar.";

    $('.plugin-selective').each(function () {
        var elemento = $(this);
        //console.log(elemento.data('json'));
        elemento.selective({
          namespace: 'addMember',
          selected: elemento.data('json'),
          local: member,
          onAfterSelected: function(e){
              //alert(elemento.val());
          },
          onAfterItemAdd: function(e){
            //alert(elemento.val());
            //alert(elemento.data('id'));
            var idLoc = elemento.data('id');
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
                    }
                }
            });
          },
          onAfterItemRemove: function(e){
            var idLoc = elemento.data('id');
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
                        s//wal('Deleted!', 'Your imaginary file has been deleted.', 'success');
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
              return '<div class=\"' + this.namespace + '-trigger-button\"><i class=\"wb-plus\"></i></div>';
            },
            listItem: function listItem(data) {
              return '<li class=\"' + this.namespace + '-list-item\"><img class=\"avatar\" src=\"' + data.avatar + '\">' + data.name + '</li>';
            },
            item: function item(data) {
              return '<li class=\"' + this.namespace + '-item\"><img class=\"avatar\" src=\"' + data.avatar + '\" title=\"' + data.name + '\">' + this.options.tpl.itemRemove.call(this) + '</li>';
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