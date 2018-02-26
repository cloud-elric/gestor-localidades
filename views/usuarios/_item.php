<?php
use app\models\Calendario;
use yii\helpers\Html;
?>

        <div class="panel-listado-col w-m"><img class="panel-listado-img" src="<?=$model->imageProfile?>" alt=""> 
            <span><?=$model->nombreCompleto?></span>
        </div>
        <div class="panel-listado-col w-m"><?=$model->txtAuthItem->description?></div>
        
        <div class="panel-listado-col w-m"> <?=Calendario::getDateComplete($model->fch_creacion)?></div>
        <div class="panel-listado-col w-m">
          <div class="btn-group" data-toggle="buttons" role="group">
            <label class="btn btn-outline btn-active active">
              <input type="radio" name="options" autocomplete="off" value="male" checked />
              Activo
            </label>
            <label class="btn btn-outline btn-inactive ">
              <input type="radio" name="options" autocomplete="off" value="female" />
              Inactivo
            </label>
          </div>
        </div>
        <div class="panel-listado-col w-x"><button type="button" class="btn btn-outline btn-success btn-sm"><i class="icon wb-plus"></i></button></div>



<!--

<div class="media">
    <div class="pr-20">
        <div class="avatar avatar-online">
        <img src="<?=$model->imageProfile?>" alt="...">
        <i class="avatar avatar-busy"></i>
        </div>
    </div>
    <div class="media-body">
        <h5 class="mt-0 mb-5">
            <?= Html::a($model->nombreCompleto, ['usuarios/update/'.$model->id_usuario])?>
            <small><?=$model->txtAuthItem->description?></small>
        </h5>
        <p>
            <i class="icon icon-color wb-envelope" aria-hidden="true"></i>
            <?=$model->txt_email?>
        </p>
        <p>
            <i class="icon icon-color wb-calendar" aria-hidden="true"></i>
            <?=Calendario::getDateComplete($model->fch_creacion)?>
        </p>
        <div>
        
        </div>
    </div>
    <div class="pl-20 align-self-center">
        <button type="button" class="btn btn-outline btn-success btn-sm">Follow</button>
    </div>
</div>-->