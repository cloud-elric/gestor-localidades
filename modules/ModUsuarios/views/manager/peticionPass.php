<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Recuperar contraseña';
$this->params['classBody'] = "page-login-v3 layout-full";
?>
<div class="panel">
	<div class="panel-body">
		<div class="brand">
			<img class="brand-img mb-40" src="<?=Url::base()?>/webAssets/images/logo.png" alt="...">
		</div>


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


		<?= Html::submitButton('<span class="ladda-label">Recuperar contraseña</span>', ["data-style"=>"zoom-in", 'class' => 'btn btn-primary btn-block btn-lg mt-20 ladda-button', 'name' => 'login-button'])
        ?>
        <div class="form-group clearfix  text-center mt-20">
			<a href="<?=Url::base()?>/login">Iniciar sesión</a>
		</div>
        


		<?php ActiveForm::end(); ?>


		<p class="soporteTxt">¿Necesitas ayuda? escribe a: <a class="no-redirect" href="mailto:soporte@2gom.com.mx?Subject=Solicitud%de%Soporte">soporte@2gom.com.mx</a></p>
	</div>
</div>
