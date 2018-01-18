<?php
use app\models\Calendario;
?>
<div class="media">
    <div class="pr-20">
        <div class="avatar avatar-online">
        <img src="<?=$model->imageProfile?>" alt="...">
        <i class="avatar avatar-busy"></i>
        </div>
    </div>
    <div class="media-body">
        <h5 class="mt-0 mb-5">
            <?=$model->nombreCompleto?>
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
</div>
