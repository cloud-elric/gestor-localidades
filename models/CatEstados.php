<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_estados".
 *
 * @property string $id_estado
 * @property string $id_pais
 * @property string $num_estado
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property double $num_latitud
 * @property double $num_longitud
 * @property string $b_habilitado
 *
 * @property CatPaises $pais
 * @property EntLocalidades[] $entLocalidades
 */
class CatEstados extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_estados';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pais', 'txt_nombre', 'txt_descripcion'], 'required'],
            [['id_pais', 'num_estado', 'b_habilitado'], 'integer'],
            [['num_latitud', 'num_longitud'], 'number'],
            [['txt_nombre'], 'string', 'max' => 45],
            [['txt_descripcion'], 'string', 'max' => 2500],
            [['id_pais'], 'exist', 'skipOnError' => true, 'targetClass' => CatPaises::className(), 'targetAttribute' => ['id_pais' => 'id_pais']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_estado' => 'Id Estado',
            'id_pais' => 'Pais',
            'num_estado' => 'Num Estado',
            'txt_nombre' => 'Estado',
            'txt_descripcion' => 'Descripcion',
            'num_latitud' => 'Latitud',
            'num_longitud' => 'Longitud',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasOne(CatPaises::className(), ['id_pais' => 'id_pais']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntLocalidades()
    {
        return $this->hasMany(EntLocalidades::className(), ['id_estado' => 'id_estado']);
    }
}
