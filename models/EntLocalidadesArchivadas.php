<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "ent_localidades_archivadas".
 *
 * @property string $id_localidad
 * @property string $id_estado
 * @property string $id_usuario
 * @property string $id_moneda
 * @property string $cms
 * @property string $txt_token
 * @property string $txt_nombre
 * @property string $txt_arrendador
 * @property string $txt_beneficiario
 * @property string $txt_calle
 * @property string $txt_colonia
 * @property string $txt_municipio
 * @property string $txt_cp
 * @property string $txt_estatus
 * @property string $txt_antecedentes
 * @property double $num_renta_actual
 * @property double $num_incremento_autorizado
 * @property double $num_pretencion_renta
 * @property double $num_incremento_cliente
 * @property double $num_pretencion_renta_cliente
 * @property string $fch_vencimiento_contratro
 * @property string $fch_creacion
 * @property string $fch_asignacion
 * @property string $b_problemas_acceso
 * @property string $b_archivada
 * @property string $b_status_localidad
 *
 * @property CatMotivosArchivar $bArchivada
 * @property CatEstados $estado
 * @property CatRegularizacionRenovacion $bStatusLocalidad
 * @property CatTiposMonedas $moneda
 * @property ModUsuariosEntUsuarios $usuario
 * @property WrkTareasArchivadas[] $wrkTareasArchivadas
 * @property WrkUsuariosLocalidadesArchivadas[] $wrkUsuariosLocalidadesArchivadas
 * @property ModUsuariosEntUsuarios[] $usuarios
 */
class EntLocalidadesArchivadas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_localidades_archivadas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_estado', 'id_usuario', 'cms', 'txt_token', 'txt_nombre', 'txt_arrendador', 'txt_beneficiario'], 'required'],
            [['id_estado', 'id_usuario', 'id_moneda', 'b_problemas_acceso', 'b_archivada', 'b_status_localidad'], 'integer'],
            [['txt_estatus', 'txt_antecedentes', 'txt_contacto', 'txt_frecuencia'], 'string'],
            [['num_renta_actual', 'num_incremento_autorizado', 'num_pretencion_renta', 'num_incremento_cliente', 'num_pretencion_renta_cliente'], 'number'],
            [['fch_vencimiento_contratro', 'fch_creacion', 'fch_asignacion'], 'safe'],
            [['cms'], 'string', 'max' => 50],
            [['txt_token'], 'string', 'max' => 70],
            [['txt_nombre', 'txt_arrendador', 'txt_beneficiario', 'txt_calle', 'txt_colonia', 'txt_municipio'], 'string', 'max' => 150],
            [['txt_cp'], 'string', 'max' => 5],
            [['b_archivada'], 'exist', 'skipOnError' => true, 'targetClass' => CatMotivosArchivar::className(), 'targetAttribute' => ['b_archivada' => 'id_motivo']],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => CatEstados::className(), 'targetAttribute' => ['id_estado' => 'id_estado']],
            [['b_status_localidad'], 'exist', 'skipOnError' => true, 'targetClass' => CatRegularizacionRenovacion::className(), 'targetAttribute' => ['b_status_localidad' => 'id_catalogo']],
            [['id_moneda'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposMonedas::className(), 'targetAttribute' => ['id_moneda' => 'id_moneda']],
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
            'id_estado' => 'Id Estado',
            'id_usuario' => 'Id Usuario',
            'id_moneda' => 'Id Moneda',
            'cms' => 'Cms',
            'txt_token' => 'Txt Token',
            'txt_nombre' => 'Nombre',
            'txt_arrendador' => 'Arrendador',
            'txt_beneficiario' => 'Txt Beneficiario',
            'txt_calle' => 'Txt Calle',
            'txt_colonia' => 'Txt Colonia',
            'txt_municipio' => 'Txt Municipio',
            'txt_cp' => 'Txt Cp',
            'txt_estatus' => 'Txt Estatus',
            'txt_antecedentes' => 'Txt Antecedentes',
            'num_renta_actual' => 'Num Renta Actual',
            'num_incremento_autorizado' => 'Num Incremento Autorizado',
            'num_pretencion_renta' => 'Num Pretencion Renta',
            'num_incremento_cliente' => 'Num Incremento Cliente',
            'num_pretencion_renta_cliente' => 'Num Pretencion Renta Cliente',
            'fch_vencimiento_contratro' => 'Fch Vencimiento Contratro',
            'fch_creacion' => 'Fecha de Creación',
            'fch_asignacion' => 'Fecha  de Asignación',
            'b_problemas_acceso' => 'B Problemas Acceso',
            'b_archivada' => 'B Archivada',
            'b_status_localidad' => 'B Status Localidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBArchivada()
    {
        return $this->hasOne(CatMotivosArchivar::className(), ['id_motivo' => 'b_archivada']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(CatEstados::className(), ['id_estado' => 'id_estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBStatusLocalidad()
    {
        return $this->hasOne(CatRegularizacionRenovacion::className(), ['id_catalogo' => 'b_status_localidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMoneda()
    {
        return $this->hasOne(CatTiposMonedas::className(), ['id_moneda' => 'id_moneda']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(ModUsuariosEntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWrkTareasArchivadas()
    {
        return $this->hasMany(WrkTareasArchivadas::className(), ['id_localidad' => 'id_localidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWrkUsuariosLocalidadesArchivadas()
    {
        return $this->hasMany(WrkUsuariosLocalidadesArchivadas::className(), ['id_localidad' => 'id_localidad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(ModUsuariosEntUsuarios::className(), ['id_usuario' => 'id_usuario'])->viaTable('wrk_usuarios_localidades_archivadas', ['id_localidad' => 'id_localidad']);
    }
}
