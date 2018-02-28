<?php

namespace app\controllers;

use Yii;
use app\models\EntLocalidades;
use app\models\EntLocalidadesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\WrkUsuariosLocalidades;
use app\modules\ModUsuarios\models\Utils;
use yii\web\Response;
use app\models\WrkTareas;
use app\models\TareasSearch;
use app\models\WrkUsuariosTareas;
use app\models\Dropbox;
use app\models\EntEstatus;

/**
 * LocalidadesController implements the CRUD actions for EntLocalidades model.
 */
class LocalidadesController extends Controller
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
     * Lists all EntLocalidades models.
     * @return mixed
     */
    public function actionIndex($token = null)
    {
        if($token){
            $user = EntUsuarios::find()->where(['txt_token'=>$token])->one();
            Yii::$app->getUser()->login($user);
        }
        $idUser = Yii::$app->user->identity->id_usuario;
        
        $searchModel = new EntLocalidadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelTarea = new TareasSearch();
        $dataProviderTarea = $searchModelTarea->search(Yii::$app->request->queryParams);

        $wrkUserTareas = WrkUsuariosTareas::find()->where(['id_usuario'=>$idUser])->select('id_tarea')->asArray()->all();        
        $tareas = WrkTareas::find()->where(['in', 'id_tarea', $wrkUserTareas])->all();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelTarea' => $searchModelTarea,
            'dataProviderTarea' => $dataProviderTarea,
            'tareas' => $tareas
        ]);
    }

    /**
     * Displays a single EntLocalidades model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new TareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$relUserLoc = new WrkUsuariosLocalidades();
        $userRel = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$id])->all();
        //$idUsersRel = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$id])->select('id_usuario')->all();

        $tareas = WrkTareas::find()->where(['id_localidad'=>$id])->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'userRel' => $userRel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tareas' => $tareas
            //'relUserLoc' => $relUserLoc,
            //'idUsersRel' => $idUsersRel
        ]);
    }

    /**
     * Creates a new EntLocalidades model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntLocalidades();
        $estatus = new EntEstatus();
        $historial = null;

        if ($model->load(Yii::$app->request->post()) && $estatus->load(Yii::$app->request->post())){
            //var_dump($_POST);exit;
            $model->id_usuario = Yii::$app->user->identity->id_usuario; 
            $model->txt_token = Utils::generateToken('tok');

            $model->fch_vencimiento_contratro = Utils::changeFormatDateInput($model->fch_vencimiento_contratro);
            $model->fch_asignacion = Utils::changeFormatDateInput($model->fch_asignacion);
            
            $dropbox = Dropbox::crearFolder("raul/".$_POST["EntLocalidades"]["txt_nombre"]);
            $decodeDropbox = json_decode(trim($dropbox), TRUE);

            if($decodeDropbox['metadata']){
                if($model->save()){
                    $estatus->id_localidad = $model->id_localidad;
                    if($estatus->save()){     
                        $relUserLoc = new WrkUsuariosLocalidades();
                        $relUserLoc->id_usuario = $model->id_usuario;
                        $relUserLoc->id_localidad = $model->id_localidad;
                        if($relUserLoc->save()){
                            return $this->redirect(['view', 'id' => $model->id_localidad]);
                        }
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'estatus' => $estatus,
            'historial' => $historial
        ]);
    }

    /**
     * Updates an existing EntLocalidades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $estatus = new EntEstatus();
        $historial = EntEstatus::find()->where(['id_localidad'=>$id])->all();

        if ($model->load(Yii::$app->request->post()) && $estatus->load(Yii::$app->request->post())) {
            $estatus->id_localidad = $model->id_localidad;
            if($model->save() && $estatus->save()){
                return $this->redirect(['view', 'id' => $model->id_localidad]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'estatus' => $estatus,
            'historial' => $historial
        ]);
    }

    /**
     * Deletes an existing EntLocalidades model.
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
     * Finds the EntLocalidades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntLocalidades the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntLocalidades::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAsignarUsuarios(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(isset($_POST['idL']) && isset($_POST['idU']) ){
            $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$_POST['idL']])->one();
            if($relacion)
                //$relacion->delete();

            $relUserLoc = new WrkUsuariosLocalidades();
            $relUserLoc->id_usuario = $_POST['idU'];
            $relUserLoc->id_localidad = $_POST['idL'];

            if($relUserLoc->save()){

                /*if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {
                    $user = EntUsuarios::findIdentity($relUserLoc->id_usuario);
                    $localidad = EntLocalidades::findOne($relUserLoc->id_localidad);

					// Enviar correo
					$utils = new Utils ();
					// Parametros para el email
					$parametrosEmail ['localidad'] = $localidad->txt_nombre;
					$parametrosEmail ['user'] = $user->getNombreCompleto ();
					
					// Envio de correo electronico
                    $utils->sendEmailAsignacion( $user->txt_email,$parametrosEmail );
                    
                    				
                }*/
                
                //return $this->redirect(['view', 'id'=>$relUserLoc->id_localidad]);
                return ['status'=>'success'];	
            }

            return ['status'=>'error'];	            
        }
        return ['status'=>'error'];
        /*if(isset($_POST['WrkUsuariosLocalidades']['id_usuario']) && isset($_POST['WrkUsuariosLocalidades']['id_localidad']) ){
            $relUserLoc = new WrkUsuariosLocalidades();
            $relUserLoc->id_usuario = $_POST['WrkUsuariosLocalidades']['id_usuario'];
            $relUserLoc->id_localidad = $_POST['WrkUsuariosLocalidades']['id_localidad'];

            if($relUserLoc->save()){

                if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {
                    $user = EntUsuarios::findIdentity($relUserLoc->id_usuario);
                    $localidad = EntLocalidades::findOne($relUserLoc->id_localidad);

					// Enviar correo
					$utils = new Utils ();
					// Parametros para el email
					$parametrosEmail ['localidad'] = $localidad->txt_nombre;
					$parametrosEmail ['user'] = $user->getNombreCompleto ();
					
					// Envio de correo electronico
                    $utils->sendEmailAsignacion( $user->txt_email,$parametrosEmail );
                    
                    				
                }
                
                return $this->redirect(['view', 'id'=>$relUserLoc->id_localidad]);	
            }
        }*/
    }

    public function actionAsignarUsuariosTareas(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(isset($_POST['idT']) && isset($_POST['idU']) ){
            $relacion = WrkUsuariosTareas::find()->where(['id_tarea'=>$_POST['idT']])->one();
            if($relacion)
                $relacion->delete();

            $relUserLoc = new WrkUsuariosTareas();
            $relUserLoc->id_usuario = $_POST['idU'];
            $relUserLoc->id_tarea = $_POST['idT'];

            if($relUserLoc->save()){

                if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {
                    $user = EntUsuarios::findIdentity($_POST['idU']);
                    $tarea = WrkTareas::find()->where(['id_tarea'=>$_POST['idT']])->one();
                    $loc = $tarea->localidad;
                    $abogado = $tarea->usuario;
                    //$tarea = WrkTareas::findOne($model->id_localidad);

					// Enviar correo
					$utils = new Utils ();
					// Parametros para el email
					$parametrosEmail ['tarea'] = $tarea->txt_nombre;
					$parametrosEmail ['loc'] = $loc->txt_nombre;
					$parametrosEmail ['user'] = $user->getNombreCompleto();
					$parametrosEmail ['abogado'] = $abogado->getNombreCompleto();
					$parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl([ 
                        'localidades/index/?token=' . $user->txt_token
                    ]);
					
					// Envio de correo electronico
                    $utils->sendEmailAsignacionTarea( $user->txt_email,$parametrosEmail );
                    
                    //return $this->redirect(['view', 'id'=>$relUserLoc->id_localidad]);
                    return ['status'=>'success'];					
                }

                return ['status'=>'error mandar correo'];	            
            }

            return ['status'=>'error guardar relacion'];
        }
        return ['status'=>'error post data'];
    }

    public function actionNotificaciones(){
        $hoy = date("Y-m-d 00:00:00");
        $tareas = WrkTareas::find()->where(['>', 'fch_due_date', $hoy])->orderBy('fch_due_date')->all();//var_dump($tareas);exit;
        $arr = [];
        
        foreach($tareas as $tarea){
            $tareaUser = $tarea->usuarios;//WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->one();
            //$user = EntUsuarios::find()->where(['id_usuario'=>$tareaUser->id_usuario])->one();

            //$arr[$user->id_usuario]['correo'] = $user->txt_email;
            //$arr[$user->id_usuario][$tarea->id_tarea] = $tarea;
            foreach($tareaUser as $user){
                $arr[$user->id_usuario]['tareas'][] = $tarea;
                if(!isset($arr[$user->id_usuario]['correo'])){
                    $arr[$user->id_usuario]['correo'] = $user->idUsuario->txt_email;
                }
                if(!isset($arr[$user->id_usuario]['nombre'])){
                    $arr[$user->id_usuario]['nombre'] = $user->idUsuario->nombreCompleto;
                }
            }            
        }

        foreach($arr as $tar){
            // Enviar correo
            $utils = new Utils ();
            $parametrosEmail = [];
            $parametrosEmail ['tareas'] = null;
            // Parametros para el email
            foreach($tar['tareas'] as $t){
                $parametrosEmail ['tareas'] .= $t->txt_nombre . "<br/><br/>";
            } 
            $parametrosEmail ['user'] = $tar['nombre'];
            
            
            // Envio de correo electronico
            $utils->sendEmailNotificacionesTareas( $tar['correo'], $parametrosEmail );
        }

        exit;
    }
}
