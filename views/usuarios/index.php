<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-usuarios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Usuarios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Rol</th>
            <th>usuario</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_item',
                'pager' => [
                    'firstPageLabel' => 'first',
                    'lastPageLabel' => 'last',
                    'nextPageLabel' => 'next',
                    'prevPageLabel' => 'previous',
                    'maxButtonCount' => 3,
                ],
            ]) ?>
        </tbody>
    </table>

    <?php /* GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_usuario',
            'txt_auth_item',
            //'txt_token',
            //'txt_imagen',
            [
                'attribute' => 'txt_username',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->txt_username,[
                        'view',
                        'id' => $data->id_usuario
                    ]);
                }
            ],
            'txt_apellido_paterno',
            //'txt_apellido_materno',
            // 'txt_auth_key',
            // 'txt_password_hash',
            // 'txt_password_reset_token',
            'txt_email:email',
            // 'fch_creacion',
            // 'fch_actualizacion',
            // 'id_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>
</div>