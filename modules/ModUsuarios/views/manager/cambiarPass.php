<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cambiar contraseña';
$this->params['classBody'] = "page-login-v3 layout-full";
?>

<div class="panel">
	<div class="panel-body">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                "template" => "{input}{label}{error}",
                "options" => [
                    "class" => "form-group form-material floating",
                    "data-plugin" => "formMaterial"
                ],
                "labelOptions" => [
                    "class" => "floating-label"
                ]
            ]
        ]); ?>

        
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label("Nueva contraseña") ?>
        
        <?= $form->field($model, 'repeatPassword')->passwordInput(['maxlength' => true])->label("Repetir contraseña") ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="ladda-label">Cambiar contraseña</label>', ["data-style"=>"zoom-in", 'class' => 'btn btn-recuperar-pass btn-block btn-lg mt-20 ladda-button', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <p class="soporteTxt">¿Necesitas ayuda? escribe a: <a class="no-redirect login-link-white" href="mailto:soporte@ovhaul.mx?Subject=Solicitud%de%Soporte">soporte@ovhaul.mx</a></p>
    </div>
</div>    
