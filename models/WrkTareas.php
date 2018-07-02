<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "wrk_tareas".
 *
 * @property string $id_tarea
 * @property string $id_usuario
 * @property string $id_tarea_padre
 * @property string $id_localidad
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property string $fch_creacion
 * @property string $fch_due_date
 * @property string $b_completa
 *
 * @property WrkComentariosTareas[] $wrkComentariosTareas
 * @property WrkDocumentos[] $wrkDocumentos
 * @property WrkHistorialTareas[] $wrkHistorialTareas
 * @property EntLocalidades $localidad
 * @property ModUsuariosEntUsuarios $usuario
 * @property WrkTareas $tareaPadre
 * @property WrkTareas[] $wrkTareas
 */
class WrkTareas extends \yii\db\ActiveRecord
{
    public $file;
    public $colaboradoresAsignados;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wrk_tareas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_tarea_padre', 'id_localidad', 'id_tipo', 'b_completa'], 'integer'],
            [['id_localidad', 'txt_nombre'], 'required'],
            [['txt_descripcion', 'txt_tarea'], 'string'],
            [['fch_creacion', 'fch_actualizacion', 'fch_due_date', 'fch_asignacion'], 'safe'],
            [['txt_nombre'], 'string', 'max' => 100],
            [['id_localidad'], 'exist', 'skipOnError' => true, 'targetClass' => EntLocalidades::className(), 'targetAttribute' => ['id_localidad' => 'id_localidad']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
            [['id_tarea_padre'], 'exist', 'skipOnError' => true, 'targetClass' => WrkTareas::className(), 'targetAttribute' => ['id_tarea_padre' => 'id_tarea']],
            //[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'doc, docx, pdf, csv, xlsx']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tarea' => 'Id Tarea',
            'id_usuario' => 'Id Usuario',
            'id_tarea_padre' => 'Id Tarea Padre',
            'id_localidad' => 'Localidad',
            'txt_nombre' => 'Nombre',
            'txt_descripcion' => 'Descripción',
            'fch_creacion' => 'Fecha creación',
            'fch_due_date' => 'Fecha Due Date',
            'b_completa' => 'B Completa',
            'id_tipo'=>'Tipo de tarea'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWrkComentariosTareas()
    {
        return $this->hasMany(WrkComentariosTareas::className(), ['id_tarea' => 'id_tarea']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWrkDocumentos()
    {
        return $this->hasMany(WrkDocumentos::className(), ['id_tarea' => 'id_tarea']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWrkHistorialTareas()
    {
        return $this->hasMany(WrkHistorialTareas::className(), ['id_tarea' => 'id_tarea']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTareaPadre()
    {
        return $this->hasOne(WrkTareas::className(), ['id_tarea' => 'id_tarea_padre']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWrkTareas()
    {
        return $this->hasMany(WrkTareas::className(), ['id_tarea_padre' => 'id_tarea']);
    }

    public function getUsuarios()
    {
        return $this->hasMany(WrkUsuariosTareas::className(), ['id_tarea' => 'id_tarea']);
    }
}
