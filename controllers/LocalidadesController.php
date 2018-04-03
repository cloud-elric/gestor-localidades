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
use app\models\ConstantesWeb;
use app\models\WrkUsuarioUsuarios;
use app\models\ResponseServices;
use app\models\CatTiposMonedas;
use app\models\CatRegularizacionRenovacion;
use app\components\AccessControlExtend;
use app\models\EntLocalidadesArchivadas;
use app\models\WrkTareasArchivadas;
use app\models\WrkUsuariosLocalidadesArchivadas;
use app\models\WrkUsuariosTareasArchivadas;


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
             'access' => [
                 'class' => AccessControlExtend::className(),
                 'only' => ['create', 'view', 'update', 'delete', 'asignar-usuario', 'asignar-usuario-eliminar', 'remover-asignacion-usuario',
                    'asignar-usuario-tarea', 'notificaciones', 'ver-tareas-localidad', 'archivar-localidad'],
                 'rules' => [
                     [
                         'actions' => ['create', 'view', 'update', 'delete', 'asignar-usuario', 'asignar-usuario-eliminar', 'remover-asignacion-usuario',
                            'asignar-usuario-tarea', 'notificaciones', 'ver-tareas-localidad', 'archivar-localidad'],
                     'allow' => true,
                         'roles' => [ConstantesWeb::ABOGADO, ConstantesWeb::COLABORADOR, ConstantesWeb::CLIENTE],
                     ],
                     
                   
                 ],
             ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all EntLocalidades models.
     * @return mixed
     */
    public function actionIndex($token = null, $tokenLoc = null)
    {
        if($token){
            $user = EntUsuarios::find()->where(['txt_token'=>$token])->one();
            Yii::$app->getUser()->login($user);
        }
        if(Yii::$app->user->isGuest){
            return $this->redirect(["//login"]);
        }

        $idUser = Yii::$app->user->identity->id_usuario;
        
        $searchModel = new EntLocalidadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        
        //LISTA DE USUARIOA PARA AGREGAR
        $selected = [];
        $directoresJuridicos = EntUsuarios::find()->where(['txt_auth_item'=>ConstantesWeb::CLIENTE,'id_status'=>2])->all();
        $i=0;
        foreach($directoresJuridicos as $directorJuridico){
            $selected[$i]['id'] = $directorJuridico->id_usuario;
            $selected[$i]['name'] = $directorJuridico->getNombreCompleto();
            $selected[$i]['avatar'] = $directorJuridico->getImageProfile();
            $i++;
        }
        
        $jsonAgregar = json_encode($selected);

        $model = new EntLocalidades();
        $flag = false;
        $estatus = new EntEstatus();
        $historial = null;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            /*'searchModelTarea' => $searchModelTarea,
            'dataProviderTarea' => $dataProviderTarea,
            'tareas' => $tareas,*/
            'jsonAgregar' => $jsonAgregar,
            'model' => $model,
            'estatus' => $estatus,
            'historial' => $historial,
            'flag' => $flag
        ]);
    }

    /**
     * Displays a single EntLocalidades model.
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
     * Creates a new EntLocalidades model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntLocalidades();
        $estatus = new EntEstatus();
        //$monedas = CatTiposMonedas::find()->where(['b_habilitado'=>1])->all();
        //$tipo = CatRegularizacionRenovacion::
        $historial = null;

        if ($model->load(Yii::$app->request->post()) && $estatus->load(Yii::$app->request->post())){
            //var_dump($_POST);exit;
            $model->id_usuario = Yii::$app->user->identity->id_usuario; 
            $model->txt_token = Utils::generateToken('tok');
            //$model->id_moneda = $_POST['group2'];

            $model->fch_vencimiento_contratro = Utils::changeFormatDateInput($model->fch_vencimiento_contratro);
            $model->fch_asignacion = Utils::changeFormatDateInput($model->fch_asignacion);
            
            $dropbox = Dropbox::crearFolder("raul/".$_POST["EntLocalidades"]["txt_nombre"]);
            $decodeDropbox = json_decode(trim($dropbox), TRUE);

            if($decodeDropbox['metadata']){
                if($model->save()){
                    $estatus->id_localidad = $model->id_localidad;
                    if($estatus->save()){     
                        return $this->redirect(['index']);
                    }
                }
            }
        }
        $flag = true;
        
        return $this->render('create', [
            'model' => $model,
            'estatus' => $estatus,
            'flag' => $flag,        
            'historial' => $historial,
            //'monedas' => $monedas
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
            $dropbox = Dropbox::crearFolder("raul/".$_POST["EntLocalidades"]["txt_nombre"]);
            $decodeDropbox = json_decode(trim($dropbox), TRUE);
            
            if($decodeDropbox['metadata']){
                if($model->save() && $estatus->save()){
                    return $this->redirect(['view', 'id' => $model->id_localidad]);
                }
            }
        }
        $tareas = true;
        $flag = true;

        return $this->render('update', [
            'model' => $model,
            'estatus' => $estatus,
            'tareas' => $tareas,
            'historial' => $historial,
            'flag' => $flag,
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
            $longArray = sizeOf($_POST['idU']);
            $idUser = null;
            for($i = $longArray - 1; $i >= 0; $i--){
                $idUser = $_POST['idU'][$i];
                break;
            }
            
            $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$_POST['idL']])->one();
            if($relacion){
                //$relacion->delete();
            }
            $relUserLoc = new WrkUsuariosLocalidades();
            $relUserLoc->id_usuario = $idUser;
            $relUserLoc->id_localidad = $_POST['idL'];

            if($relUserLoc->save()){
                $user = EntUsuarios::findIdentity($relUserLoc->id_usuario);
                if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {
                    $localidad = EntLocalidades::findOne($relUserLoc->id_localidad);

					// Enviar correo
					$utils = new Utils ();
					// Parametros para el email
					$parametrosEmail ['localidad'] = $localidad->txt_nombre;
                    $parametrosEmail ['user'] = $user->getNombreCompleto ();
                    $parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                        'localidades/index/?token=' . $user->txt_token
                    ]);
					
					// Envio de correo electronico
                    $utils->sendEmailAsignacion( $user->txt_email,$parametrosEmail );
                    				
                }
                
                //return $this->redirect(['view', 'id'=>$relUserLoc->id_localidad]);
                return [
                    'status' => 'success'
                ];	
            }

            return ['status'=>'error'];	            
        }
        return ['status'=>'error'];
    }

    public function actionAsignarUsuariosEliminar(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(isset($_POST['idL']) && isset($_POST['idU']) ){
            
            $idUser =  $_POST['idU'];
            $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$_POST['idL']])->andWhere(['not in', 'id_usuario', $_POST['idU']])->one();
            if($relacion){
                if($relacion->delete()){
                    return [
                        'status' => 'success'
                    ];	
                }
                return ['status'=>'error'];
            
            }
            return ['status'=>'error'];	            
        }else{
            if(isset($_POST['idL'])){
                $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$_POST['idL']])->one();
                if($relacion){
                    if($relacion->delete()){
                        return [
                            'status' => 'success'
                        ];	
                    }

                    return ['status'=>'error'];
                }
            }
        }

        return ['status'=>'error post'];
    }

    public function actionRemoverAsignacionTarea(){
        $respuesta = new ResponseServices();
        if(isset($_POST['idT']) && isset($_POST['idU']) ){
            $longArray = sizeOf($_POST['idU']);
            $idUser = null;
            for($i = $longArray - 1; $i >= 0; $i--){
                $idUser = $_POST['idU'][$i];
                break;
            }

            $relacion = WrkUsuariosTareas::find()->where(['id_tarea'=>$_POST['idT']])->one();
            if($relacion){
                $respuesta->status = "success";
                $respuesta->message = "Se ha eliminado la asignacion a la tarea";
                $relacion->delete();
            }    
        }else{
            $respuesta->message= "No se enviaron los datos por post";
        }


        return $respuesta;
    }

    public function actionAsignarUsuariosTareas(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        if(isset($_POST['idT']) && isset($_POST['idU']) ){
            $longArray = sizeOf($_POST['idU']);
            $idUser = null;
            for($i = $longArray - 1; $i >= 0; $i--){
                $idUser = $_POST['idU'][$i];
                break;
            }

            $relacion = WrkUsuariosTareas::find()->where(['id_tarea'=>$_POST['idT']])->one();
            if($relacion)
                $relacion->delete();

            $relUserLoc = new WrkUsuariosTareas();
            $relUserLoc->id_usuario = $idUser;
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
                        'localidades/index/?token=' . $user->txt_token . '&tokenLoc=' . $loc->txt_token
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

    public function actionVerTareasLocalidad($id){

        $localidad = $this->findModel($id);
        $usuarioLogueado = EntUsuarios::getUsuarioLogueado();
        // Obtiene las tareas del colaborador si no seran todas
        if($usuarioLogueado->txt_auth_item==ConstantesWeb::COLABORADOR){
            $tareasColaborador = WrkUsuariosTareas::find()->where(['id_usuario'=>$usuarioLogueado->id_usuario])->select('id_tarea')->asArray()->all();
            $tareas = WrkTareas::find()->where(["in", 'id_tarea', $tareasColaborador])->andWhere(['id_localidad' => $localidad->id_localidad])->all();
        }else{
            $tareas = $localidad->wrkTareas;
        }
        
        // Obtiene al director asigando a la localidad para conseguir a sus colaboradores
        $directorAsignado = WrkUsuariosLocalidades::find()->where(["id_localidad"=>$id])->one();
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
                $idUsuarios = WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->select('id_usuario')->asArray()->all();
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
        
        return $this->renderAjax("ver-tareas-localidad-clear", ["localidad"=>$localidad, "tareas"=>$tareas, "jsonAgregar"=>$jsonAgregar]);
    }

    public function actionArchivarLocalidad($id, $mot){
        $response = new ResponseServices();

        $localidad = $this->findModel($id);

        $archivada = new EntLocalidadesArchivadas();
        $archivada->attributes = $localidad->attributes;
        $archivada->id_localidad = $localidad->id_localidad;
        $archivada->b_archivada = $mot;

        $transaction = Yii::$app->db->beginTransaction();
		try{
            if($archivada->save()){
                $tareas = $localidad->wrkTareas;
                if($tareas){
                    foreach($tareas as $tarea){
                        $tareaArchivada = new WrkTareasArchivadas();
                        $tareaArchivada->attributes = $tarea->attributes;
                        $tareaArchivada->id_tarea = $tarea->id_tarea;
                        $tareaArchivada->id_localidad = $archivada->id_localidad;
                        
                        if ($tareaArchivada->save()){
                            $usersTareas = WrkUsuariosTareas::find()->where(['id_tarea'=>$tarea->id_tarea])->all();
                            if($usersTareas){
                                foreach($usersTareas as $userTarea){
                                    $userTareaArchivada = new WrkUsuariosTareasArchivadas();
                                    $userTareaArchivada->attributes = $userTarea->attributes;
                                    $userTareaArchivada->id_tarea = $userTarea->id_tarea;
                                    
                                    if(!$userTareaArchivada->save()){
                                        return $response;
                                    }
                                    $userTarea->delete();
                                }
                            }

                            $usersLocs = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$localidad->id_localidad])->all();
                            if($usersLocs){
                                foreach($usersLocs as $userLoc){
                                    $userLocArchivada = new WrkUsuariosLocalidadesArchivadas();
                                    $userLocArchivada->attributes = $userLoc->attributes;

                                    if(!$userLocArchivada->save()){
                                        return $response;
                                    }
                                    $userLoc->delete();
                                }
                            }
                        }
                        $tarea->delete();
                    }
                }
                $localidad->delete();
                $transaction->commit ();
                $response->status = 'success';
                $response->message = $id;

                return $response;
			}
			$transaction->commit();
		}catch ( \Exception $e ) {
			$transaction->rollBack ();
			throw $e;
		}
        
        return $response;
    }
}
