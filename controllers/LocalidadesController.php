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
use app\models\Calendario;
use app\models\EntLocalidadesArchivadasSearch;
use app\config\ConstantesDropbox;
use app\models\CatColonias;


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
                'only' => [
                    'create', 'update', 'delete', 'asignar-usuario', 'asignar-usuario-eliminar', 'remover-asignacion-usuario',
                    'asignar-usuario-tarea',  'ver-tareas-localidad', 'archivar-localidad'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create', 'update', 'delete', 'asignar-usuario', 'asignar-usuario-eliminar', 'remover-asignacion-usuario',
                            'asignar-usuario-tarea',  'ver-tareas-localidad', 'archivar-localidad'
                        ],
                        'allow' => true,
                        'roles' => [ConstantesWeb::ABOGADO, ConstantesWeb::ASISTENTE],
                    ],
                    [
                        'actions' => [
                            'asignar-usuario-tarea',  'ver-tareas-localidad'
                        ],
                        'allow' => true,
                        'roles' => [ConstantesWeb::COLABORADOR, ConstantesWeb::CLIENTE],
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
        if ($token) {
            $user = EntUsuarios::find()->where(['txt_token' => $token])->one();

            if ($user->id_status == EntUsuarios::STATUS_BLOCKED) {
                Yii::$app->session->setFlash('error', "El usuario con el email: '" . $user->txt_email . "' ha sido bloqueado.");
                return $this->redirect(["//login"]);
            }
            Yii::$app->getUser()->login($user);
        }
        if (Yii::$app->user->isGuest) {
            return $this->redirect(["//login"]);
        }

        $idUser = Yii::$app->user->identity->id_usuario;

        $searchModel = new EntLocalidadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        
        //LISTA DE USUARIOA PARA AGREGAR
        $selected = [];
        $directoresJuridicos = EntUsuarios::find()->where(['txt_auth_item' => ConstantesWeb::CLIENTE, 'id_status' => 2])->all();
        $i = 0;
        foreach ($directoresJuridicos as $directorJuridico) {
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
    public function actionView($id, $token = null)
    {
        if ($token) {
            $user = EntUsuarios::find()->where(['txt_token' => $token])->one();
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
        $model->id_moneda = 1;
        $hoy = Utils::getFechaActual();

        $model->fch_asignacion = Utils::changeFormatDateNormal($hoy);

        $estatus = new EntEstatus();

        $historial = null;

        if ($model->load(Yii::$app->request->post()) && $estatus->load(Yii::$app->request->post())) { //print_r($_POST);exit;

            /**
             * Guardar datos si vienen de forma manual
             */
            if($_POST['tipo_ubicacion'] == 1){
                $model->txt_cp = $_POST['EntLocalidades']['textoCP'];
                $model->txt_calle = $_POST['EntLocalidades']['textoCalle'];
                $model->texto_colonia = $_POST['EntLocalidades']['textoColonia'];
                $model->texto_estado = $_POST['EntLocalidades']['textoEstado'];
                $model->txt_municipio = $_POST['EntLocalidades']['textoMun'];
            }
            //exit;
            
            $model->id_usuario = Yii::$app->user->identity->id_usuario;
            $model->txt_token = Utils::generateToken('tok');
            $model->fch_creacion = $hoy;
            

            if($model->validate()){
                $model->fch_vencimiento_contratro = Utils::changeFormatDateInput($model->fch_vencimiento_contratro);
                $model->fch_asignacion = Utils::changeFormatDateInput($model->fch_asignacion);

                $dropbox = Dropbox::crearFolder(ConstantesDropbox::NOMBRE_CARPETA . $_POST["EntLocalidades"]["txt_nombre"]);
                $decodeDropbox = json_decode(trim($dropbox), true);
                
                if(isset($decodeDropbox['metadata'])){
                    // if($model->validate()){
                    //     echo "Validado";exit;
                    // }echo "no validado";exit;
                    if ($model->save()) {
                        if(!empty($_POST['EntEstatus']['txt_estatus'])){
                            $estatus->id_localidad = $model->id_localidad;
                            if ($estatus->save()) {
                                return $this->redirect(['index']);
                            }
                        }else{
                            return $this->redirect(['index']);
                        }
                    }else{
                        print_r($model->errors);
                        exit;
                    }
                }else{
                    print_r($decodeDropbox);exit;
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
        $historial = EntEstatus::find()->where(['id_localidad' => $id])->all();
        $nombreOriginal = $model->txt_nombre;
        
        if ($model->load(Yii::$app->request->post()) && $estatus->load(Yii::$app->request->post())) { //print_r($model->fch_asignacion);print_r($model->fch_vencimiento_contratro);exit;

             /**
             * Guardar datos si vienen de forma manual
             */
            if($_POST['tipo_ubicacion'] == 1){
                $model->txt_cp = $_POST['EntLocalidades']['textoCP'];
                $model->txt_calle = $_POST['EntLocalidades']['textoCalle'];
                $model->texto_colonia = $_POST['EntLocalidades']['textoColonia'];
                $model->txt_municipio = $_POST['EntLocalidades']['textoMun'];
                $model->texto_estado = $_POST['EntLocalidades']['$textoEstado'];

                $model->txt_colonia = null;
            }

            $model->fch_vencimiento_contratro = Utils::changeFormatDateInput($model->fch_vencimiento_contratro);
            $model->fch_asignacion = Utils::changeFormatDateInput($model->fch_asignacion);print_r($model->fch_asignacion);//print_r($model->fch_vencimiento_contratro);exit;

            if(!empty($_POST['EntEstatus']['txt_estatus'])){
                $estatus->id_localidad = $model->id_localidad;
            }
            
            if ($model->validate()) {
                if ($nombreOriginal != $model->txt_nombre) {
                    // @TODO
                    //Esto debe de renombrar la carpeta y no crear un nuevo folder marca error si tiene el mismo nombre
                    $dropbox = Dropbox::moverArchivo("/". ConstantesDropbox::NOMBRE_CARPETA . $nombreOriginal, "/". ConstantesDropbox::NOMBRE_CARPETA . $_POST["EntLocalidades"]["txt_nombre"]);

                    $decodeDropbox = json_decode(trim($dropbox), true);

                    if (isset($decodeDropbox['metadata'])) {
                        if($model->save()){
                            if(!empty($_POST['EntEstatus']['txt_estatus'])){
                                if(!$estatus->save()){
                                    print_r($estatus->errors);exit;
                                }
                            }
                            return $this->redirect(['index']);                                    
                        }else{
                            print_r($model->errors);exit;
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "Ocurrió un problema con la comunicación de dropbox. Si el problema persiste contacté a soporteœ2gom.com.mx.");

                    }
                } else {
                    if($model->save()){
                        if(!empty($_POST['EntEstatus']['txt_estatus'])){
                            if(!$estatus->save()){
                                print_r($estatus->errors);exit;                                
                            }
                        }
                        return $this->redirect(['index']);
                    }else{
                        print_r($model->errors);exit;
                    }
                }
            }

        }//print_r($_POST);exit;

        $tareas = true;
        $flag = true;

        $model->fch_vencimiento_contratro = Utils::changeFormatDateNormal($model->fch_vencimiento_contratro);
        $model->fch_asignacion = Utils::changeFormatDateNormal($model->fch_asignacion);
        
        if($model->txt_colonia){
            $model->tipoUbicacion = 0;
        }else{
            $model->tipoUbicacion = 1;
            $model->textoCP = $model->txt_cp;
            $model->textoColonia = $model->texto_colonia;
            $model->textoEstado = $model->texto_estado;
            $model->textoCalle = $model->txt_calle;
            $model->textoMun = $model->txt_municipio;

            $model->txt_cp = null;
            $model->txt_municipio = null;
            $model->id_estado = null;
            $model->txt_calle = null;
            $model->id_estado = null;
        }

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

    public function actionAsignarUsuarios()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['idL']) && isset($_POST['idU'])) {
            $longArray = sizeOf($_POST['idU']);
            $idUser = null;
            for ($i = $longArray - 1; $i >= 0; $i--) {
                $idUser = $_POST['idU'][$i];
                break;
            }

            $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad' => $_POST['idL']])->one();
            if ($relacion) {
                //$relacion->delete();
            }
            $relUserLoc = new WrkUsuariosLocalidades();
            $relUserLoc->id_usuario = $idUser;
            $relUserLoc->id_localidad = $_POST['idL'];

            if ($relUserLoc->save()) {
                $user = EntUsuarios::findIdentity($relUserLoc->id_usuario);
                if (Yii::$app->params['modUsuarios']['mandarCorreoActivacion']) {
                    $localidad = EntLocalidades::findOne($relUserLoc->id_localidad);

					// Enviar correo
                    $utils = new Utils();
					// Parametros para el email
                    $parametrosEmail['localidad'] = $localidad->txt_nombre;
                    $parametrosEmail['user'] = $user->getNombreCompleto();
                    $parametrosEmail['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                        'localidades/index/?token=' . $user->txt_token
                    ]);
					
					// Envio de correo electronico
                    $utils->sendEmailAsignacion($user->txt_email, $parametrosEmail);

                }
                
                //return $this->redirect(['view', 'id'=>$relUserLoc->id_localidad]);
                return [
                    'status' => 'success'
                ];
            }

            return ['status' => 'error'];
        }
        return ['status' => 'error'];
    }

    public function actionAsignarUsuariosEliminar()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['idL']) && isset($_POST['idU'])) {

            $idUser = $_POST['idU'];
            $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad' => $_POST['idL']])->andWhere(['not in', 'id_usuario', $_POST['idU']])->one();
            if ($relacion) {
                if ($relacion->delete()) {
                    return [
                        'status' => 'success'
                    ];
                }
                return ['status' => 'error'];

            }
            return ['status' => 'error'];
        } else {
            if (isset($_POST['idL'])) {
                $relacion = WrkUsuariosLocalidades::find()->where(['id_localidad' => $_POST['idL']])->one();
                if ($relacion) {
                    if ($relacion->delete()) {
                        return [
                            'status' => 'success'
                        ];
                    }

                    return ['status' => 'error'];
                }
            }
        }

        return ['status' => 'error post'];
    }

    public function actionRemoverAsignacionTarea()
    {
        $respuesta = new ResponseServices();
        if (isset($_POST['idT']) && isset($_POST['idU'])) {
            $longArray = sizeOf($_POST['idU']);
            $idUser = null;
            for ($i = $longArray - 1; $i >= 0; $i--) {
                $idUser = $_POST['idU'][$i];
                break;
            }

            $relacion = WrkUsuariosTareas::find()->where(['id_tarea' => $_POST['idT']])->one();
            if ($relacion) {
                $tar = $relacion->tarea;
                $tar->fch_asignacion = null;
                $tar->save();

                $respuesta->status = "success";
                $respuesta->message = "Se ha eliminado la asignacion a la tarea";
                $relacion->delete();
            }
        } else {
            $respuesta->message = "No se enviaron los datos por post";
        }


        return $respuesta;
    }

    public function actionAsignarUsuariosTareas()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['idT']) && isset($_POST['idU'])) {
            $longArray = sizeOf($_POST['idU']);
            $idUser = null;
            for ($i = $longArray - 1; $i >= 0; $i--) {
                $idUser = $_POST['idU'][$i];
                break;
            }            

            $relacion = WrkUsuariosTareas::find()->where(['id_tarea' => $_POST['idT']])->one();
            if ($relacion)
                $relacion->delete();

            $relUserLoc = new WrkUsuariosTareas();
            $relUserLoc->id_usuario = $idUser;
            $relUserLoc->id_tarea = $_POST['idT'];

            if ($relUserLoc->save()) {

                if (Yii::$app->params['modUsuarios']['mandarCorreoActivacion']) {
                    $user = EntUsuarios::findIdentity($_POST['idU']);
                    $tarea = WrkTareas::find()->where(['id_tarea' => $_POST['idT']])->one();
                    $loc = $tarea->localidad;
                    $abogado = $tarea->usuario;
                    //$tarea = WrkTareas::findOne($model->id_localidad);

                    $tarea->fch_asignacion = date("Y-m-d H:i:s");
                    $tarea->save();

					// Enviar correo
                    $utils = new Utils();
					// Parametros para el email
                    $parametrosEmail['tarea'] = $tarea->txt_nombre;
                    $parametrosEmail['loc'] = $loc->txt_nombre;
                    $parametrosEmail['user'] = $user->getNombreCompleto();
                    $parametrosEmail['abogado'] = $abogado->getNombreCompleto();
                    $parametrosEmail['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                        'localidades/index/?token=' . $user->txt_token . '&tokenLoc=' . $loc->txt_token
                    ]);
					
					// Envio de correo electronico
                    $utils->sendEmailAsignacionTarea($user->txt_email, $parametrosEmail);
                    
                    //return $this->redirect(['view', 'id'=>$relUserLoc->id_localidad]);
                    return ['status' => 'success'];
                }

                return ['status' => 'error mandar correo'];
            }

            return ['status' => 'error guardar relacion'];
        }

        return ['status' => 'error post data'];
    }

    public function actionNotificaciones()
    {
        $respuesta = new ResponseServices();
        $hoy = date("Y-m-d 00:00:00");
        $hoy = date("Y-m-d 00:00:00", strtotime($hoy . '-8 day'));
        $tareas = WrkTareas::find()->where(['<', 'fch_creacion', $hoy])->andWhere(['txt_path'=>null])->andWhere(['txt_tarea'=>null])->andWhere(['b_completa'=>0])->orderBy('fch_creacion')->all();
        $arr = [];

        foreach ($tareas as $tarea) {
            $colaboradores = $tarea->usuarios;
            $localidad = $tarea->localidad;
            $directores = $localidad->wrkUsuariosLocalidades;

            $hoy = date("Y-m-d");
            $dias = (strtotime($hoy) - strtotime($tarea->fch_creacion))/86400;
	        $dias = abs($dias); $dias = floor($dias);

            foreach ($directores as $director) {
                $usuarioDirector = $director->usuario;

                $arr[$usuarioDirector->id_usuario]['localidades'][$localidad->id_localidad]["nombreLocalidad"] = $localidad->txt_nombre;
                $arr[$usuarioDirector->id_usuario]['localidades'][$localidad->id_localidad]["cms"]=$localidad->cms;
                $arr[$usuarioDirector->id_usuario]['localidades'][$localidad->id_localidad]["token"]=$localidad->txt_token;
                $arr[$usuarioDirector->id_usuario]['localidades'][$localidad->id_localidad]['tareas'][$tarea->id_tarea]['nombre'] = $tarea->txt_nombre;
                $arr[$usuarioDirector->id_usuario]['localidades'][$localidad->id_localidad]['tareas'][$tarea->id_tarea]['dias'] = $dias;
                $arr[$usuarioDirector->id_usuario]["directorEmail"] = $usuarioDirector->txt_email;
                $arr[$usuarioDirector->id_usuario]["directorNombre"] = $usuarioDirector->nombreCompleto;
                $arr[$usuarioDirector->id_usuario]["directorToken"] = $usuarioDirector->txt_token;
            }
        }
        //print_r($arr);exit;

        foreach($arr as $tar){ //print_r($tar['localidades']);exit;
            // Enviar correo
            $utils = new Utils();
            $parametrosEmail = [];

            $parametrosEmail ['localidades'] = $tar['localidades'];//print_r($tar);exit;

            $email = $tar['directorEmail'];
            $parametrosEmail ['user'] = $tar['directorNombre'];
            $parametrosEmail['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                'localidades/index/?token=' . $tar["directorToken"]
            ]);

            if($utils->sendEmailNotificacionesTareas( $email, $parametrosEmail )){
                $respuesta->status = "success";
                $respuesta->message = "Enviados con exito";
                $respuesta->result = $arr;
            }else{
                return $respuesta;
            }
        }

        return $respuesta;
    }

    public function actionVerTareasLocalidad($id)
    {

        $localidad = $this->findModel($id);
        $usuarioLogueado = EntUsuarios::getUsuarioLogueado();
        // Obtiene las tareas del colaborador si no seran todas
        if ($usuarioLogueado->txt_auth_item == ConstantesWeb::COLABORADOR) {
            $tareasColaborador = WrkUsuariosTareas::find()->where(['id_usuario' => $usuarioLogueado->id_usuario])->select('id_tarea')->asArray()->all();
            $tareas = WrkTareas::find()->where(["in", 'id_tarea', $tareasColaborador])->andWhere(['id_localidad' => $localidad->id_localidad])->all();
        } else {
            $tareas = $localidad->wrkTareas;
        }
        
        // Obtiene al director asigando a la localidad para conseguir a sus colaboradores
        $directorAsignado = WrkUsuariosLocalidades::find()->where(["id_localidad" => $id])->one();
        $colaboradores = [];
        if ($directorAsignado) {
            $colaboradores = WrkUsuarioUsuarios::find()->where(["id_usuario_padre" => $directorAsignado->id_usuario])->all();
        }
        $selected = [];
        $i = 0;
        foreach ($colaboradores as $colaborador) {
            $colaborador = $colaborador->usuarioHijo;
            $selected[$i]['id'] = $colaborador->id_usuario;
            $selected[$i]['name'] = $colaborador->nombreCompleto;
            $selected[$i]['avatar'] = $colaborador->imageProfile;
            $i++;
        }
        $jsonAgregar = json_encode($selected);

        // Obtiene a los usuarios asignados a la tarea
        if (!($usuarioLogueado->txt_auth_item == ConstantesWeb::COLABORADOR)) {
            $tareasA = [];
            foreach ($tareas as $tarea) {
                $idUsuarios = WrkUsuariosTareas::find()->where(['id_tarea' => $tarea->id_tarea])->select('id_usuario')->asArray()->all();
                $usersSeleccionados = EntUsuarios::find()->where(['in', 'id_usuario', $idUsuarios])->all();
                $colaboradoresTarea = [];
                $i = 0;
                foreach ($usersSeleccionados as $userSeleccionado) {
                    $colaboradoresTarea[$i]['id'] = $userSeleccionado->id_usuario;
                    $colaboradoresTarea[$i]['name'] = $userSeleccionado->getNombreCompleto();
                    $colaboradoresTarea[$i]['avatar'] = $userSeleccionado->getImageProfile();
                    $i++;
                }
                $tarea->colaboradoresAsignados = json_encode($colaboradoresTarea);
                $tareasA[] = $tarea;
            }

            $tareas = $tareasA;
        }

        return $this->renderAjax("ver-tareas-localidad-clear", ["localidad" => $localidad, "tareas" => $tareas, "jsonAgregar" => $jsonAgregar]);
    }

    public function actionArchivarLocalidad($id, $mot)
    {
        $response = new ResponseServices();

        $localidad = $this->findModel($id);

        $archivada = new EntLocalidadesArchivadas();
        $archivada->attributes = $localidad->attributes;
        $archivada->id_localidad = $localidad->id_localidad;
        //print_r($archivada);exit;
        $archivada->b_archivada = $mot;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($archivada->save()) {
                $tareas = $localidad->wrkTareas;
                if ($tareas) {
                    foreach ($tareas as $tarea) {
                        $tareaArchivada = new WrkTareasArchivadas();
                        $tareaArchivada->attributes = $tarea->attributes;
                        $tareaArchivada->id_tarea = $tarea->id_tarea;
                        $tareaArchivada->id_localidad = $archivada->id_localidad;

                        if ($tareaArchivada->save()) {
                            $usersTareas = WrkUsuariosTareas::find()->where(['id_tarea' => $tarea->id_tarea])->all();
                            if ($usersTareas) {
                                foreach ($usersTareas as $userTarea) {
                                    $userTareaArchivada = new WrkUsuariosTareasArchivadas();
                                    $userTareaArchivada->attributes = $userTarea->attributes;
                                    $userTareaArchivada->id_tarea = $userTarea->id_tarea;

                                    if (!$userTareaArchivada->save()) {
                                        $transaction->rollBack();
                                        echo "wqwq";
                                        return $response;
                                    }
                                    $userTarea->delete();
                                }
                            }
                            
                            $usersLocs = WrkUsuariosLocalidades::find()->where(['id_localidad' => $localidad->id_localidad])->one();
                            //print_r($usersLocs);exit;
                            if ($usersLocs) {
                                // foreach ($usersLocs as $userLoc) {
                                    $userLocArchivada = new WrkUsuariosLocalidadesArchivadas();
                                    $userLocArchivada->attributes = $usersLocs->attributes;

                                    if (!$userLocArchivada->save()) {
                                        $transaction->rollBack();
                                        
                                        return $response;
                                    }
                                    $usersLocs->delete();
                                // }
                            }
                        } else {
                            $transaction->rollBack();
                            echo "xdxddd";
                            return $response;
                        }
                        $tarea->delete();
                    }
                }
                $localidad->delete();
                $transaction->commit();
                $response->status = 'success';
                $response->message = $id;

                return $response;
            }else{
                $transaction->rollBack();
                echo "hyhyhyhy";print_r($archivada->errors);exit;
                return $response;
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        echo "cdfvbghn";
        return $response;
    }

    public function actionExportarLocalidades(){
        $nuevoFichero = fopen('Localidades.csv', 'w+');
        fputs($nuevoFichero, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        if($nuevoFichero){
            $localidades = EntLocalidades::find()->all();
            
            $delimiter = ",";
            $campos = [
                'CMS',
                'Nombre',
                'Arrendador',
                'Beneficiario',
                'Contacto',
                'Antecedentes',
                'Historial de estatus',

                'Codigo postal',
                'Colonia',
                'Delegación/Municipio',
                'Estado',
                'Domicilio',

                'Fecha vencimiento contrato',
                'Fecha asignacion',
                'Tipo de contrato',
                'Renta actual',
                'Porcentaje de incremento preautorizado',
                'Renta pre-autorizada',
                'Porcentaje de incremento solicitado por arrendador',
                'Pretensión de renta del arrendador',
                'Frecuencia de pago',
                'Moneda',
                'Problemas de acceso'
            ];

            fputcsv($nuevoFichero, $campos, $delimiter);

            foreach($localidades as $localidad){
                $estado;
                if($localidad->id_estado){
                    $estado = $localidad->estado;
                }
                $colonia;
                if($localidad->txt_colonia){
                    $colonia = CatColonias::find()->where(['id_colonia'=>$localidad->txt_colonia])->one();
                }
                $usuario = $localidad->usuario;
                $moneda = $localidad->moneda;
                $status = $localidad->bStatusLocalidad;
                $estatusLoc = EntEstatus::find()->where(['id_localidad'=>$localidad->id_localidad])->all();
                $problemaAcceso = $localidad->b_problemas_acceso ? 'Si' : 'No';
                
                $i = 1;
                $estatusConcat = '';

                if($estatusLoc){
                    foreach($estatusLoc as $estatus){
                        $estatusConcat .= $i . ".- " . $estatus->txt_estatus . " ";
                        $i++;
                    }
                }else{
                    $estatusConcat = "No hay estatus";
                }//echo $estatusConcat;exit;
                
                $datos = [
                    $localidad->cms,
                    $localidad->txt_nombre,
                    $localidad->txt_arrendador,
                    $localidad->txt_beneficiario,
                    $localidad->txt_contacto,
                    $localidad->txt_antecedentes,
                    $estatusConcat,

                    $localidad->txt_cp,
                    $localidad->txt_colonia ? $colonia->txt_nombre : $localidad->texto_colonia,
                    $localidad->txt_municipio,
                    $localidad->id_estado ? $estado->txt_nombre : $localidad->texto_estado,
                    $localidad->txt_calle,

                    $localidad->fch_vencimiento_contratro,
                    $localidad->fch_asignacion,
                    $status->txt_nombre,
                    $localidad->num_renta_actual,
                    $localidad->num_incremento_autorizado,
                    $localidad->num_pretencion_renta,
                    $localidad->num_incremento_cliente,
                    $localidad->num_pretencion_renta_cliente,
                    $localidad->txt_frecuencia,
                    $moneda->txt_moneda,
                    $problemaAcceso
                ];

                fputcsv($nuevoFichero, $datos, $delimiter);
            }
            fseek($nuevoFichero, 0);
            header('Content-Type: text/csv');
            header("Content-disposition: attachment; filename=\"Localidades.csv\"");

            fpassthru($nuevoFichero);exit;
        }
    }

    public function actionEditarEstatus($id = null){
        $response = new ResponseServices();

        if(isset($_POST['txt_estatus'])){
            if(!empty($_POST['txt_estatus'])){
                $estatus = EntEstatus::find()->where(['id_estatus'=>$id])->one();
                
                if($estatus){
                    $estatus->txt_estatus = $_POST['txt_estatus'];
                    if($estatus->save()){
                        $response->status = 'success';
                        $response->message = 'Estatus actualizado correctamente';
                    }else{
                        $response->result = $estatus->errors;
                    }
                }
            }
        }

        return $response;
    }
}
