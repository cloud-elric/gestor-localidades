<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CatEstados;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\Utils;

/* @var $this yii\web\View */
/* @var $model app\models\EntLocalidades */

$this->title = $model->txt_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Ent Localidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-localidades-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            [
                'label' => 'Usuario',
                'value' => function($data){
                    $user = EntUsuarios::find()->where(['id_usuario'=>$data->id_usuario])->one();
                    return $user->txt_username;
                }
            ],
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
            [
                'label' => 'Archivada',
                'value' => function($data){
                    if($data->b_archivada == 0){
                        return "No";
                    }else{
                        return "Si";
                    }
                }
            ],
        ],
    ]) ?>

</div>
