<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "wrk_usuarios_tareas".
 *
 * @property string $id_usuario
 * @property string $id_tarea
 */
class WrkUsuariosTareas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wrk_usuarios_tareas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_tarea'], 'required'],
            [['id_usuario', 'id_tarea'], 'integer'],
            [['id_usuario', 'id_tarea'], 'unique', 'targetAttribute' => ['id_usuario', 'id_tarea']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'id_tarea' => 'Id Tarea',
        ];
    }

    public function getIdUsuario()
    {
        return $this->hasOne(EntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }

    public function getTarea()
    {
        return $this->hasOne(WrkTareas::className(), ['id_tarea' => 'id_tarea']);
    }
}
