<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CatEstados;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\modules\ModUsuarios\models\Utils;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = $model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-view">

    

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_localidad], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_localidad], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Estado',
                'value' => function($data){
                    $estado = CatEstados::find()->where(['id_estado'=>$data->id_estado])->one();
                    return $estado->txt_nombre;
                }
            ],
            /*[
                'label' => 'Usuario',
                'value' => function($data){
                    $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])->one();
                    return $user->txt_username;
                }
            ],*/
            'txt_nombre',
            'txt_arrendador',
            'txt_beneficiario',
            'txt_calle',
            'txt_colonia',
            'txt_municipio',
            'txt_cp',
            'txt_estatus:ntext',
            'txt_antecedentes:ntext',
            'num_renta_actual',
            'num_incremento_autorizado',
            [
                'label' => 'Fecha Vencimiento Contratro',
                'value' => function($data){
                    return Utils::changeFormatDate($data->fch_vencimiento_contratro);
                }
            ],
            [
                'label' => 'Fecha Creacion',
                'value' => function($data){
                    return Utils::changeFormatDate($data->fch_creacion);
                }
            ],
            [
                'label' => 'Fecha Asignacion',
                'value' => function($data){
                    return Utils::changeFormatDate($data->fch_asignacion);
                }
            ],
            [
                'label' => 'Problemas Acceso',
                'value' => function($data){
                    if($data->b_problemas_acceso == 0){
                        return "No";
                    }else{
                        return "Si";
                    }
                }
            ],
            /*[
                'label' => 'Archivada',
                'value' => function($data){
                    if($data->b_archivada == 0){
                        return "No";
                    }else{
                        return "Si";
                    }
                }
            ]*/
        ],
    ]) ?>
</div>
<?php if($userRel){ ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3>Clientes asignados</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Apellido paterno</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($userRel as $user){ 
                                $usuario = EntUsuarios::find()->where(['id_usuario'=>$user->id_usuario])->one();    
                            ?>
                                <tr>
                                    <td><?= $usuario->txt_username ?></td>
                                    <td><?= $usuario->txt_apellido_paterno ?></td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="ent-localidades-form">
    <?php $form = ActiveForm::begin([
        'action' => ['localidades/asignar-usuarios'],
        'options' => ['method' => 'post']
    ]); ?>

    <?= $form->field($relUserLoc, 'id_usuario')->dropDownList(ArrayHelper::map(EntUsuarios::find()->where(['txt_auth_item'=>'cliente'])->andWhere(['not in', 'id_usuario', $idUsersRel])->orderBy('txt_username')->asArray()->all(), 'id_usuario', 'txt_username'),['prompt' => 'Seleccionar usuario']) ?>

    <?= $form->field($relUserLoc, 'id_localidad')->hiddenInput(['value' => $model->id_localidad])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Asignar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
