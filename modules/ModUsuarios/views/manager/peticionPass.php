<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Recuperar contraseña';
$this->params['classBody'] = "page-login-v3 layout-full";
?>

<div class="panel">
	<div class="panel-body">
	<?php if (Yii::$app->session->hasFlash('success')): ?>
			<div class="alert alert-success alert-dismissable dark">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			<h4><i class="icon fa fa-check"></i>Correo enviado</h4>
			<?= Yii::$app->session->getFlash('success') ?>
			</div>
		<?php endif; ?>
		<?php 
		$form = ActiveForm::begin([
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
		]); 
		?>

		<?= $form->field($model, 'username')->textInput(["class"=>"form-control"]) ?>


		<?= Html::submitButton('<span class="ladda-label">Recuperar contraseña</span>', ["data-style"=>"zoom-in", 'class' => 'btn btn-recuperar-pass btn-block btn-lg mt-20 ladda-button', 'name' => 'login-button'])
        ?>
        <div class="form-group clearfix  text-center mt-20">
			<a class="login-link-gray login-link-lg" href="<?=Url::base()?>/login">Iniciar sesión</a>
		</div>
        


		<?php ActiveForm::end(); ?>


		<p class="soporteTxt">¿Necesitas ayuda? escribe a: <a class="no-redirect login-link-white" href="mailto:soporte@ovhaul.mx?Subject=Solicitud%de%Soporte">soporte@ovhaul.mx</a></p>
	</div>
</div>
