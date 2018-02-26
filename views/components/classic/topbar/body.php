<?php
use yii\widgets\Breadcrumbs;
?>
<!-- Page -->
<div class="page ryg-page">

  <!-- <div class="ryg-page-mask"></div> -->

  <div class="page-header">
      <!-- <h1 class="page-title"><?=$this->title?></h1> -->

      <!-- <?=Breadcrumbs::widget([
        'homeLink' => [
          'label' => '<i class="icon pe-home"></i>Inicio',
          'url' => Yii::$app->homeUrl,
          'encode' => false// Requested feature
        ],
        'itemTemplate'=>'<li class="breadcrumb-item">{link}</li>',
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'options'=>['class'=>'breadcrumb breadcrumb-arrow']
      ]);?> -->

      <div class="page-header-actions">
        <?=isset($this->params['headerActions']) ? $this->params['headerActions'] : ''?>
      </div>
    </div>

    <div class="page-content">
      <?=$content?>
    </div>

  </div>
  <!-- End Page -->