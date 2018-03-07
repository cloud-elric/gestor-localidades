<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CatEstados;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ListView;
use app\assets\AppAsset;
use app\models\ConstantesWeb;

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

<!-- Panel -->
<div class="panel panel-localidades">
    <div class="panel-body">

        <div class="panel-search">
            <h3 class="panel-search-title">Listado de localidades</h3>
            <div class="panel-search-int">
                
                <?= $this->render('_search', [
                    'model' => $searchModel,
                    //'estatus' => $estatus            
                ]); ?>

                
                <?php if(Yii::$app->user->identity->txt_auth_item == "abogado"){ ?>
                    <?= Html::a('<i class="icon wb-plus"></i> Crear Localidades', ['create'], ['class' => 'btn btn-success btn-add']) ?>
                <?php } ?>
            </div>
        </div>

        <div class="panel-listado">
            <div class="panel-listado-head">
                <div class="panel-listado-col w-x"></div>
                <div class="panel-listado-col w-m">Nombre</div>
                <div class="panel-listado-col w-m">Última actualización</div>
                <div class="panel-listado-col w-m">Fecha de asignación</div>
                <div class="panel-listado-col w-m">Arrendedor</div>
                <?php if(Yii::$app->user->identity->txt_auth_item == ConstantesWeb::ABOGADO){ ?>
                    <div class="panel-listado-col w-m">Responsables</div>
                <?php } ?>
                <div class="panel-listado-col w-s">Acciones</div>
            </div>

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemLocalidades',
            ]);?>

        </div>
    </div>
</div>

<?php

$this->registerJs("

$(document).ready(function(){
    var member = ".$jsonAgregar.";

    $('.plugin-selective').each(function () {
        var elemento = $(this);
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



