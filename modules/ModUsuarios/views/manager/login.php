<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['classBody'] = "page-login-v3 layout-full";

?>

<div class="panel">
	<div class="panel-body">
		<div class="brand">
			<img class="brand-img mb-40" src="<?=Url::base()?>/webAssets/images/logo.png" alt="...">
		</div>


		<?php 
		$form = ActiveForm::begin([
			'id' => 'form-ajax',
			'enableAjaxValidation' => true,
			'enableClientValidation'=>true,
			'errorCssClass'=>"has-danger",
			'fieldConfig' => [
				"template" => "{input}{label}{error}",
				"options" => [
					"class" => "form-group form-material floating",
					"data-plugin" => "formMaterial"
				],
				"labelOptions" => [
					"class" => "form-control-label floating-label"
				]
			]
		]); 
		?>

		<?= $form->field($model, 'username')->textInput(["class"=>"form-control"]) ?>

		<?= $form->field($model, 'password')->passwordInput(["class"=>"form-control"])?>

		<div class="form-group clearfix">
			<a class="float-right" href="forgot-password.html">¿Olvidaste tu contraseña?</a>
		</div>

		<?= Html::submitButton('<span class="ladda-label">Ingresar</span>', ["data-style"=>"zoom-in", 'class' => 'btn btn-primary btn-block btn-lg mt-40 ladda-button', 'name' => 'login-button'])
		?>

		<?php ActiveForm::end(); ?>


		<p class="soporteTxt">¿Necesitas ayuda? escribe a: <a class="no-redirect" href="mailto:soporte@2gom.com.mx?Subject=Solicitud%de%Soporte">soporte@2gom.com.mx</a></p>
	</div>
</div>
