<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntLocalidades;
use app\models\ConstantesWeb;

/**
 * EntLocalidadesSearch represents the model behind the search form of `app\models\EntLocalidades`.
 */
class EntLocalidadesSearch extends EntLocalidades
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_localidad', 'id_estado', 'id_usuario', 'b_problemas_acceso', 'b_archivada', 'b_status_localidad'], 'integer'],
            [['txt_token', 'cms', 'txt_nombre', 'txt_arrendador', 'txt_beneficiario', 'txt_calle', 'txt_colonia', 'txt_municipio', 'txt_cp', 'txt_estatus', 'txt_antecedentes', 'fch_vencimiento_contratro', 'fch_creacion', 'fch_asignacion'], 'safe'],
            [['num_renta_actual', 'num_incremento_autorizado'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $user = Yii::$app->user->identity;
        $query = EntLocalidades::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($user->txt_auth_item == ConstantesWeb::ABOGADO){
            // grid filtering conditions
            $query->andFilterWhere([
                'id_usuario' => $user->id_usuario,
                'id_localidad' => $this->id_localidad
                ]);
        }
        if($user->txt_auth_item == ConstantesWeb::CLIENTE){
            $loc = WrkUsuariosLocalidades::find()->select('id_localidad')->where(['id_usuario'=>$user->id_usuario])->asArray();//var_dump($loc);exit;
            // grid filtering conditions
            $query->andFilterWhere(['in', 'id_localidad', $loc]);
        }

        if($user->txt_auth_item == ConstantesWeb::COLABORADOR){
            $grupoTrabajo = WrkUsuarioUsuarios::find()->where(['id_usuario_hijo'=>$user->id_usuario])->one();
            $loc = WrkUsuariosLocalidades::find()->select('id_localidad')->where(['id_usuario'=>$grupoTrabajo->id_usuario_padre])->asArray();//var_dump($loc);exit;
            // grid filtering conditions
            $query->andFilterWhere(['in', 'id_localidad', $loc]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_estado' => $this->id_estado,
            'num_renta_actual' => $this->num_renta_actual,
            'num_incremento_autorizado' => $this->num_incremento_autorizado,
            'fch_vencimiento_contratro' => $this->fch_vencimiento_contratro,
            'fch_creacion' => $this->fch_creacion,
            'fch_asignacion' => $this->fch_asignacion,
            'b_problemas_acceso' => $this->b_problemas_acceso,
            'b_archivada' => $this->b_archivada,
            'b_status_localidad' => $this->b_status_localidad,
        ]);

        $query->andFilterWhere(['like', 'txt_token', $this->txt_token])
            ->andFilterWhere(['like', 'txt_nombre', $this->txt_nombre])
            ->andFilterWhere(['like', 'txt_arrendador', $this->txt_arrendador])
            ->andFilterWhere(['like', 'txt_beneficiario', $this->txt_beneficiario])
            ->andFilterWhere(['like', 'txt_calle', $this->txt_calle])
            ->andFilterWhere(['like', 'txt_colonia', $this->txt_colonia])
            ->andFilterWhere(['like', 'txt_municipio', $this->txt_municipio])
            ->andFilterWhere(['like', 'txt_cp', $this->txt_cp])
            ->andFilterWhere(['like', 'txt_estatus', $this->txt_estatus])
            ->andFilterWhere(['like', 'txt_antecedentes', $this->txt_antecedentes])
            ->andFilterWhere(['like', 'cms', $this->cms]);

        return $dataProvider;
    }
}
