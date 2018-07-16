<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cambiar contraseña';
$this->params['classBody'] = "page-login-v2 layout-full page-login";
?>

<div class="page-brand-info">
	<img class="logo-login" src="<?=Url::base()?>/webAssets/images/overhaul-login.png" alt="Overhaul">
</div>

<div class="page-login-main animation-slide-right animation-duration-1">

	<div class="page-login-v2-mask"></div>

	<div class="page-login-main-cont">

		<h3 class="title-datos">Porfavor Ingresa tus datos</h3>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>


        <?= $form->field($model, 'password')->passwordInput(["class"=>"form-control", 'placeholder'=>'Nueva contraseña'])->label(false) ?>

        <?= $form->field($model, 'repeatPassword')->passwordInput(["class"=>"form-control", 'placeholder'=>'Repetir contraseña'])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('<span class="ladda-label">Cambiar contraseña</label>', ["data-style"=>"zoom-in", 'class' => 'btn btn-recuperar-pass btn-block btn-lg mt-20 ladda-button', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <p class="soporteTxt">¿Necesitas ayuda? escribe a: <a class="no-redirect login-link-white" href="mailto:soporte@ovhaul.mx?Subject=Solicitud%de%Soporte">soporte@ovhaul.mx</a></p>
    </div>
</div>    
