<?php
use yii\widgets\Breadcrumbs;
?>
<!-- Page -->
<div class="page ryg-page">
  <div class="page-header">
      <!-- <h1 class="page-title"><?=$this->title?></h1> -->

      <div class="breadcrumb">
        <h3 class="breadcrumb-title">Listado de localidades</h3>
        <div class="breadcrumb-search">
          <form class="breadcrumb-search-form">
            <input type="text" class="breadcrumb-search-form-select" placeholder="Buscar por nombre">
            <input type="text" class="breadcrumb-search-form-input ml-35" placeholder="Cliente">
            <input type="text" class="breadcrumb-search-form-input" placeholder="Estado">
            <input type="text" class="breadcrumb-search-form-input" placeholder="Status">
            <input type="text" class="breadcrumb-search-form-input" placeholder="Tipo">
          </form>
        </div>
      </div>

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