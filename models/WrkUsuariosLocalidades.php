<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "wrk_usuarios_localidades".
 *
 * @property string $id_localidad
 * @property string $id_usuario
 *
 * @property EntLocalidades $localidad
 * @property ModUsuariosEntUsuarios $usuario
 */
class WrkUsuariosLocalidades extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wrk_usuarios_localidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_localidad', 'id_usuario'], 'required'],
            [['id_localidad', 'id_usuario'], 'integer'],
            [['id_localidad', 'id_usuario'], 'unique', 'targetAttribute' => ['id_localidad', 'id_usuario']],
            [['id_localidad'], 'exist', 'skipOnError' => true, 'targetClass' => EntLocalidades::className(), 'targetAttribute' => ['id_localidad' => 'id_localidad']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_localidad' => 'Id Localidad',
            'id_usuario' => 'Id Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(EntLocalidades::className(), ['id_localidad' => 'id_localidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(EntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }
}
