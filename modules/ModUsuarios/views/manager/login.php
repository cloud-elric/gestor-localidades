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
	<?php if (Yii::$app->session->hasFlash('error')): ?>
			<div class="alert alert-danger alert-dismissable dark">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			<h4><i class="icon fa fa-warning"></i>Espera</h4>
			<?= Yii::$app->session->getFlash('error') ?>
			</div>
		<?php endif; ?>
		<?php 
		$form = ActiveForm::begin([
			'id' => 'form-ajax',
			'enableAjaxValidation' => true,
			'enableClientValidation'=>true,
			'errorCssClass'=>"has-danger",
			// 'fieldConfig' => [
			// 	"template" => "{input}{label}{error}",
			// 	"options" => [
			// 		"class" => "form-group form-material floating",
			// 		"data-plugin" => "formMaterial"
			// 	],
			// 	"labelOptions" => [
			// 		"class" => "form-control-label floating-label"
			// 	]
			// ]
		]); 
		?>

		<?= $form->field($model, 'username')->textInput(["class"=>"form-control"]) ?>

		<?= $form->field($model, 'password')->passwordInput(["class"=>"form-control"])?>

		<div class="form-group clearfix">
			<a class="float-right login-link-gray" href="<?=Url::base()?>/peticion-pass">¿Olvidaste tu contraseña?</a>
		</div>

		<?= Html::submitButton('<span class="ladda-label">Ingresar al sistema</span>', ["data-style"=>"zoom-in", 'class' => 'btn btn-primary btn-block btn-lg btn-login ladda-button', 'name' => 'login-button'])
		?>

		<?php ActiveForm::end(); ?>


		<p class="soporteTxt">¿Necesitas ayuda? escribe a: <a class="no-redirect login-link-white" href="mailto:ayuda@ryg.com.mx?Subject=Solicitud%de%Soporte">ayuda@ryg.com.mx</a></p>
	</div>
</div>
