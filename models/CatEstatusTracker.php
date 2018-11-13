<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_estatus_tracker".
 *
 * @property string $id_estatus_tracker
 * @property string $txt_estatus_tracker
 * @property string $b_habilitado
 *
 * @property EntLocalidades[] $entLocalidades
 * @property EntLocalidadesArchivadas[] $entLocalidadesArchivadas
 */
class CatEstatusTracker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_estatus_tracker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_estatus_tracker'], 'required'],
            [['b_habilitado'], 'integer'],
            [['txt_estatus_tracker'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_estatus_tracker' => 'Id Estatus Tracker',
            'txt_estatus_tracker' => 'Txt Estatus Tracker',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntLocalidades()
    {
        return $this->hasMany(EntLocalidades::className(), ['id_estatus_tracker' => 'id_estatus_tracker']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntLocalidadesArchivadas()
    {
        return $this->hasMany(EntLocalidadesArchivadas::className(), ['id_estatus_tracker' => 'id_estatus_tracker']);
    }
}
