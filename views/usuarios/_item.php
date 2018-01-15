<?php
use yii\helpers\Html;
?>  


<tr>
    <td><?=  $model->txt_auth_item ?> </td>
    <td><?= Html::a($model->txt_username, ['usuarios/view/'.$model->id_usuario]) ?> </td>
    <td><?= $model->txt_apellido_paterno ?></td>
</tr>
    

