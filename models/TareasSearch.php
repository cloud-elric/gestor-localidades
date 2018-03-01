<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WrkTareas;

/**
 * TareasSearch represents the model behind the search form of `app\models\WrkTareas`.
 */
class TareasSearch extends WrkTareas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tarea', 'id_usuario', 'id_tarea_padre', 'id_localidad', 'b_completa'], 'integer'],
            [['txt_nombre', 'txt_descripcion', 'fch_creacion', 'fch_due_date'], 'safe'],
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
    public function search($params, $id=null)
    {
        $user = Yii::$app->user->identity;
        $idUser = $user->id_usuario;
        $query = WrkTareas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if($user->txt_auth_item==="abogado"){
            $query->andFilterWhere(['id_localidad'=>$id]);
        }else{
            $wrkUserTareas = WrkUsuariosTareas::find()->where(['id_usuario'=>$idUser])->select('id_tarea')->asArray()->all(); 
            $query->andFilterWhere(['in', 'id_tarea', $wrkUserTareas]);
        } 

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
           
            $query->andFilterWhere([
            'id_usuario' => $this->id_usuario,
            'id_tarea_padre' => $this->id_tarea_padre,
            'id_localidad' => $this->id_localidad,
            'fch_creacion' => $this->fch_creacion,
            'fch_due_date' => $this->fch_due_date,
            'b_completa' => $this->b_completa,
        ]);

        $query->andFilterWhere(['like', 'txt_nombre', $this->txt_nombre])
            ->andFilterWhere(['like', 'txt_descripcion', $this->txt_descripcion]);

        return $dataProvider;
    }
}
