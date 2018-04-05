<?php

namespace app\controllers;

use Yii;
use app\models\EntLocalidadesArchivadas;
use app\models\EntLocalidadesArchivadasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\WrkUsuariosTareasArchivadas;
use app\models\WrkTareasArchivadas;
use app\models\ConstantesWeb;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\WrkUsuariosLocalidadesArchivadas;
use app\models\WrkUsuariosLocalidades;
use app\models\WrkUsuarioUsuarios;
use app\models\ResponseServices;
use app\models\EntLocalidades;
use app\models\WrkTareas;
use app\models\WrkUsuariosTareas;

/**
 * ArchivadasController implements the CRUD actions for EntLocalidadesArchivadas model.
 */
class ArchivadasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all EntLocalidadesArchivadas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EntLocalidadesArchivadasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);      

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single EntLocalidadesArchivadas model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $token=null)
    {
        if($token){
            $user = EntUsuarios::find()->where(['txt_token'=>$token])->one();
            Yii::$app->getUser()->login($user);
        }
        
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            //'relUserLoc' => $relUserLoc,
            //'idUsersRel' => $idUsersRel
        ]);
    }

    /**
     * Creates a new EntLocalidadesArchivadas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntLocalidadesArchivadas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_localidad]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EntLocalidadesArchivadas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_localidad]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EntLocalidadesArchivadas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EntLocalidadesArchivadas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntLocalidadesArchivadas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntLocalidadesArchivadas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionVerTareasLocalidad($id){

        $localidad = $this->findModel($id);
        $usuarioLogueado = EntUsuarios::getUsuarioLogueado();
        // Obtiene las tareas del colaborador si no seran todas
        if($usuarioLogueado->txt_auth_item==ConstantesWeb::COLABORADOR){
            $tareasColaborador = WrkUsuariosTareasArchivadas::find()->where(['id_usuario'=>$usuarioLogueado->id_usuario])->select('id_tarea')->asArray()->all();
            $tareas = WrkTareasArchivadas::find()->where(["in", 'id_tarea', $tareasColaborador])->andWhere(['id_localidad' => $localidad->id_localidad])->all();
        }else{
            $tareas = $localidad->wrkTareasArchivadas;
        }
        
        // Obtiene al director asigando a la localidad para conseguir a sus colaboradores
        $directorAsignado = WrkUsuariosLocalidadesArchivadas::find()->where(["id_localidad"=>$id])->one();
        $colaboradores = [];
        if($directorAsignado){
            $colaboradores = WrkUsuarioUsuarios::find()->where(["id_usuario_padre"=>$directorAsignado->id_usuario])->all();
        }
        $selected = [];
        $i = 0;
        foreach($colaboradores as $colaborador){
            $colaborador = $colaborador->usuarioHijo;
            $selected[$i]['id'] = $colaborador->id_usuario;
            $selected[$i]['name'] = $colaborador->nombreCompleto;
            $selected[$i]['avatar'] = $colaborador->imageProfile;
            $i++;
        }
        $jsonAgregar = json_encode($selected);

        // Obtiene a los usuarios asignados a la tarea
        if(!($usuarioLogueado->txt_auth_item==ConstantesWeb::COLABORADOR)){
            $tareasA = [];
            foreach($tareas as $tarea){
                $idUsuarios = WrkUsuariosTareasArchivadas::find()->where(['id_tarea'=>$tarea->id_tarea])->select('id_usuario')->asArray()->all();
                $usersSeleccionados = EntUsuarios::find()->where(['in', 'id_usuario', $idUsuarios])->all();
                $colaboradoresTarea = [];
                $i=0;
                foreach($usersSeleccionados as $userSeleccionado){
                    $colaboradoresTarea[$i]['id'] = $userSeleccionado->id_usuario;
                    $colaboradoresTarea[$i]['name'] = $userSeleccionado->getNombreCompleto();
                    $colaboradoresTarea[$i]['avatar'] = $userSeleccionado->getImageProfile();
                    $i++;
                }
                $tarea->colaboradoresAsignados = json_encode($colaboradoresTarea);
                $tareasA[]=$tarea;
            }

            $tareas = $tareasA;
        }
        
        return $this->renderAjax("ver-tareas-archivadas-clear", ["localidad"=>$localidad, "tareas"=>$tareas, "jsonAgregar"=>$jsonAgregar]);
    }

    public function actionDesarchivarLocalidad($id = null){
        $response = new ResponseServices();
        $archivada = $this->findModel($id);

        $localidad = new EntLocalidades();
        $localidad->attributes = $archivada->attributes;
        $transaction = EntLocalidades::getDb()->beginTransaction();
        try{
            if($localidad->save()){
                $tareasArchivadas = $archivada->wrkTareasArchivadas;
                foreach($tareasArchivadas as $tareaArchivada){
                    $tarea = new WrkTareas();
                    $tarea->attributes = $tareaArchivada->attributes;
                    $tarea->id_localidad = $localidad->id_localidad;

                    if(!$tarea->save()){
                        $transaction->rollBack ();
                        print_r($tarea);exit;
                        return $response;
                    }else{
                        $userTareaArchivada = WrkUsuariosTareasArchivadas::find()->where(['id_tarea'=>$tareaArchivada->id_tarea])->one();
                        $userTarea = new WrkUsuariosTareas();
                        $userTarea->id_usuario = $userTareaArchivada->id_usuario;
                        $userTarea->id_tarea = $tarea->id_tarea;

                        if(!$userTarea->save()){
                            $transaction->rollBack ();
                            print_r($userTarea);exit;                            
                            return $response;
                        }
                    }
                    $tareaArchivada->delete();
                }
                $userLocArchivada = WrkUsuariosLocalidadesArchivadas::find()->where(['id_localidad'=>$archivada->id_localidad])->one();
                $userLoc = new WrkUsuariosLocalidades();
                $userLoc->id_localidad = $localidad->id_localidad;
                $userLoc->id_usuario = $userLocArchivada->id_usuario;
                
                if(!$userLoc->save()){
                    $transaction->rollBack ();
                    print_r($userLoc);exit;
                    return $response;
                }
            }else{
                $transaction->rollBack();
                print_r($localidad);exit;
                return respuesta;
            }
            $archivada->delete();
            $transaction->commit ();
            $response->status = 'success';
            $response->message = $id;

            return $response;
        }catch(\Exception $e) {
			$transaction->rollBack ();
			throw $e;
        }

        return $response; 
    }
}
