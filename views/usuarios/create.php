<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */

$this->title = 'Crear usuario';
$this->params['breadcrumbs'][] = [
    'label' => '<i class="icon pe-users"></i> Usuarios', 
    'encode' => false,
    'template'=>'<li class="breadcrumb-item">{link}</li>',
    'url' => ['index'], 
  ];
$this->params['breadcrumbs'][] = [
    'label' => '<i class="icon wb-plus"></i>'.$this->title, 
    'encode' => false,
    'template'=>'<li class="breadcrumb-item">{link}</li>', 
  ];

  $this->params['classBody'] = "site-navbar-small";  

  $this->registerJsFile(
    '@web/webAssets/js/sign-up.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
  );

  $this->registerCssFile(
    '@web/webAssets/css/signUp.css',
    ['depends' => [\yii\web\JqueryAsset::className()]]
  );
?>
<div class="panel panel-usuarios-create">
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'roles'=>$roles
        ]) ?>
    </div>
</div>