<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cambiar contraseña';
$this->params['classBody'] = "site-navbar-small page-user ryg-body";
?>
<div class="page-login">
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="panel">
            <div class="panel-heading">
                <h2 class="panel-title white">Cambiar contraseña</h2>
            </div>
            <div class="panel-body">
                <?php 
                $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' =>[
                        "class"=>"usuarios-cambiar-pass",
                    ],
                ]); ?>

                        
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label("Nueva contraseña") ?>
                
                <?= $form->field($model, 'repeatPassword')->passwordInput(['maxlength' => true])->label("Repetir contraseña") ?>

                <div class="form-group">
                    <?= Html::submitButton('<span class="ladda-label">Cambiar contraseña</label>', ["data-style"=>"zoom-in", 'class' => 'btn btn-recuperar-pass btn-block btn-lg mt-20 ladda-button', 'name' => 'login-button']) ?>
                </div>

                <p class="soporte-ayuda">¿Necesitas ayuda? escribe a: <a class="no-redirect login-link-white" href="mailto:soporte@ovhaul.mx?Subject=Solicitud%de%Soporte">soporte@ovhaul.mx</a></p>
                <?php ActiveForm::end(); ?>
            </div>  
        </div>
        
    </div>
</div>    
</div>