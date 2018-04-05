<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\CatMotivosArchivar;
use app\assets\AppAsset;

?>
<!-- Modal -->
<div id="myModal" class="modal modal-motivo-archivar fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Motivo por el cual archiva esta localidad</h2>
      </div>
      <div class="modal-body">
        <p>Seleccionar:</p>
        <?= Html::dropDownList('id_motivo', null, ArrayHelper::map(CatMotivosArchivar::find()->where(['b_habilitado'=>1])->all(), 'id_motivo', 'txt_motivo'), ['prompt' => 'Seleccionar motivo', 'id' => 'motivo_archivar_localidad']) ?>
        <input type="hidden" id="url_loc" value="">
      </div>
      <div class="modal-footer">
        <button type="button" id="js_aceptar_archivar" class="btn btn-primary" data-dismiss="modal"><i class="icon wb-inbox" aria-hidden="true"></i> Archivar</button>
      </div>
    </div>

  </div>
</div>