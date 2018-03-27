<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\CatMotivosArchivar;
use app\assets\AppAsset;

?>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Seleccionar motivo por el cual archiva esta localidad</h4>
      </div>
      <div class="modal-body">
        <p>Seleccionar.</p>
        <?= Html::dropDownList('id_motivo', null, ArrayHelper::map(CatMotivosArchivar::find()->where(['b_habilitado'=>1])->all(), 'id_motivo', 'txt_motivo'), ['prompt' => 'Seleccionar motivo', 'id' => 'motivo_archivar_localidad']) ?>
      </div>
      <div class="modal-footer">
        <button type="button" id="js_aceptar_archivar" class="btn btn-primary" data-dismiss="modal">Arvhivar</button>
      </div>
    </div>

  </div>
</div>