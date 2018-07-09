<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "ent_localidades".
 *
 * @property string $id_localidad
 * @property string $id_estado
 * @property string $id_usuario
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
 * @property string $fch_vencimiento_contratro
 * @property string $fch_creacion
 * @property string $fch_asignacion
 * @property string $b_problemas_acceso
 * @property string $b_archivada
 *
 * @property CatEstados $estado
 * @property ModUsuariosEntUsuarios $usuario
 * @property WrkTareas[] $wrkTareas
 */
class EntLocalidades extends \yii\db\ActiveRecord
{
    public $textoCP;
    public $textoColonia;
    public $textoMun;
    public $textoEstado;
    public $textoCalle;
    public $tipoUbicacion;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_localidades';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['num_incremento_autorizado'], 'required', 'message'=>'Campo requerido',
                'when' => function ($model) {
                    return $model->b_status_localidad == 0;
                }/*, 'whenClient' => "function (attribute, value) {
                    
                    return $('#entusuarios-txt_auth_item').val()=='usuario-cliente';
                }"*/
            ],
            [
                ['textoColonia', 'textoCP', 'textoMun', 'textoCalle', 'textoEstado'], 'required', 'message'=>'Campo requerido',
                'when' => function ($model) {
                    return $model->tipoUbicacion == 1;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#js-manual').prop('checked');
                }"
            ],
            [
                ['txt_cp', 'txt_calle', 'txt_municipio'], 'required', 'message'=>'Campo requerido',
                'when' => function ($model) {
                    return $model->tipoUbicacion == 0;
                }, 'whenClient' => "function (attribute, value) {
                    
                    return $('#js-automatico').prop('checked');
                }"
            ],
            [
                ['txt_nombre'],
                'trim'
            ],
            [[/*'id_estado',*/ 'id_usuario', 'cms', 'txt_token', 'txt_nombre', 'txt_arrendador', 'txt_beneficiario', /*'txt_cp', 'txt_calle', 'txt_colonia', 'txt_municipio',*/ 'num_renta_actual', 'fch_vencimiento_contratro', 'fch_asignacion', 'txt_frecuencia'], 'required'],
            [['id_estado', 'id_usuario', 'id_moneda', 'b_problemas_acceso', 'b_archivada', 'b_status_localidad'], 'integer'],
            [['txt_estatus', 'txt_antecedentes', 'txt_contacto', 'txt_frecuencia'], 'string'],
            [['num_renta_actual', 'num_incremento_autorizado', 'num_pretencion_renta', 'num_incremento_cliente', 'num_pretencion_renta_cliente'], 'number'],
            [['fch_vencimiento_contratro', 'fch_creacion', 'fch_asignacion', 'tipoUbicacion'], 'safe'],
            [['cms'], 'string', 'max' => 50], 
            [['txt_token'], 'string', 'max' => 70],
            [['txt_nombre', 'txt_arrendador', 'txt_beneficiario', 'txt_calle', 'txt_colonia', 'txt_municipio', 'txt_contacto', 'texto_colonia', 'texto_estado'], 'string', 'max' => 150],
            [['txt_cp', 'textoCP'], 'string', 'max' => 5],
            [['txt_nombre'], 'unique', 'message'=>'El nombre de la localidad ya existe'],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => CatEstados::className(), 'targetAttribute' => ['id_estado' => 'id_estado']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
            [['b_status_localidad'], 'exist', 'skipOnError' => true, 'targetClass' => CatRegularizacionRenovacion::className(), 'targetAttribute' => ['b_status_localidad' => 'id_catalogo']],
            [['id_moneda'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposMonedas::className(), 'targetAttribute' => ['id_moneda' => 'id_moneda']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_localidad' => 'Id Localidad',
            'id_estado' => 'Estado',
            'id_usuario' => 'Usuario',
            'id_moneda' => 'Tipo de moneda',
            'txt_token' => 'Txt Token',
            'cms' => 'Cms',
            'txt_nombre' => 'Nombre localidad',
            'txt_arrendador' => 'Arrendador',
            'txt_beneficiario' => 'Beneficiario',
            'txt_calle' => 'Calle',
            'txt_colonia' => 'Colonia',
            'txt_municipio' => 'Municipio',
            'txt_cp' => 'Código postal',
            'txt_estatus' => 'Estatus',
            'txt_antecedentes' => 'Antecedentes',
            'txt_contacto' => 'Contacto',
            'txt_frecuencia' => 'Frecuencia de pago',
            'num_renta_actual' => 'Renta actual',
            'num_incremento_autorizado' => 'Porcentaje de incremento preautorizado',
            'num_pretencion_renta' => 'Renta pre-autorizada',
            'num_incremento_cliente' => 'Porcentaje de incremento solicitado por arrendador',
            'num_pretencion_renta_cliente' => 'Pretensión de renta del arrendador',
            'fch_vencimiento_contratro' => 'Fecha vencimiento contrato',
            'fch_creacion' => 'Fecha creación',
            'fch_asignacion' => 'Fecha asignación',
            'b_problemas_acceso' => 'Problemas acceso',
            'b_status_localidad' => 'Tipo de Contrato',
            'b_archivada' => 'Archivada',

            'textoCP' => 'Código postal',
            'textoColonia' => 'Colonia',
            'textoMun' => 'Municipio',
            'textoEstado' => 'Estado',
            'textoCalle' => 'Calle'
        ];
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
    public function getEstado()
    {
        return $this->hasOne(CatEstados::className(), ['id_estado' => 'id_estado']);
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
    public function getWrkTareas()
    {
        return $this->hasMany(WrkTareas::className(), ['id_localidad' => 'id_localidad']);
    }

    /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getWrkUsuariosLocalidades() 
   { 
       return $this->hasMany(WrkUsuariosLocalidades::className(), ['id_localidad' => 'id_localidad']); 
   } 
 
   /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getUsuarios() 
   { 
       return $this->hasMany(EntUsuarios::className(), ['id_usuario' => 'id_usuario'])->viaTable('wrk_usuarios_localidades', ['id_localidad' => 'id_localidad']); 
   }

   public function getUsuariosDirectores() 
   { 
       return $this->hasMany(EntUsuarios::className(), ['id_usuario' => 'id_usuario'])->viaTable('wrk_usuarios_localidades', ['id_localidad' => 'id_localidad']); 
   } 

}
