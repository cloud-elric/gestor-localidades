<?php

namespace app\controllers;

use Yii;
use app\models\WrkTareas;
use app\models\TareasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Dropbox;
use yii\web\UploadedFile;
use app\models\EntLocalidades;
use app\modules\ModUsuarios\models\Utils;
use app\models\ResponseServices;
use app\modules\ModUsuarios\models\EntUsuarios;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\ConstantesWeb;
use app\models\WrkTareasArchivadas;

/**
 * TareasController implements the CRUD actions for WrkTareas model.
 */
class TareasController extends Controller
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
     * Lists all WrkTareas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WrkTareas model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCrearTarea(){
        $respuesta = new ResponseServices();
        $model = new WrkTareas();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            $model->id_usuario = EntUsuarios::getUsuarioLogueado()->id_usuario;
            if($model->save()){ 
                $templateItem = '<li class="list-group-item js-tarea-'.$model->id_tarea.'" data-tareakey="'.$model->id_tarea.'">                        
                                    <div class="col-sm-12 col-md-12 col-separacion js_descargar_archivo-'.$model->id_tarea.'">
                                        <div class="tarea-fechas"> 
                                            <div class="tarea-actualizacion">
                                                <p class="borrar js_btn_eliminar_tarea js_btn_eliminar_tarea-'.$model->id_tarea.'" data-id="'.$model->id_tarea.'">Borrar</p>
                                            </div>
                                        </div>
                                        
                                        <form id="form-tarea-nombre'.$model->id_tarea.'" class="tarea-actions form-tareas">
                                            <div class="tarea-check">
                                                <div class="checkbox-custom checkbox-warning">                                                    
                                                    <input type="checkbox" id="check-nombre" class="js-completar-tarea" data-token="'.$model->id_tarea.'" name="checkbox">
                                                    <label for="check-nombre" class="task-title" style="width:100%"></label>
                                                </div>
                                            </div>
                                            <div class="tarea-member addMember-cont">
                                                <select multiple="multiple" class="plugin-selective-tareas" data-localidad="'.$model->id_localidad.'" data-id="'.$model->id_tarea.'" data-json="[]"/>
                                            </div>

                                            <div class="form-tarea-abogado">
                                                <div class="form-groupes"> 
                                                    <div class="form-group form-group-row"> 
                                                        <textarea class="form-control form-tarea-input js-editar-nombre-tarea" data-id="'.$model->id_tarea.'">'.$model->txt_nombre.'</textarea>
                                                        <div class="help-block"></div>
                                                    </div>
                                                    <p class="form-p form-tarea-label">'.$model->txt_nombre.'</p>
                                                    <div class="form-tarea-edit">
                                                        <i class="icon wb-pencil icon-edit js-tarea-icon-edit" aria-hidden="true"></i>
                                                        <i class="icon wb-check icon-save js-tarea-icon-save" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </li>';
                $respuesta->status = "success";
                $respuesta->message = "Tarea guardada";
                $respuesta->result = $templateItem;
                
            }else{
                $respuesta->message = "No se pudo guardar la tarea";
                $respuesta->result = $model->errors;
            }

        }else{
            $respuesta->message = "No se enviaron los parametros necesarios";
            $respuesta->result = $_POST;
        }

        return $respuesta;
    }

    /**
     * Creates a new WrkTareas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idTar)
    {
        $idUser = Yii::$app->user->identity->id_usuario;
        $model = new WrkTareas();

        if ($model->load(Yii::$app->request->post())){

            $fileDropbox = UploadedFile::getInstance($model, 'file');

            $dropbox = Dropbox::subirArchivo($fileDropbox);
            $decodeDropbox = json_decode(trim($dropbox), TRUE);
            //echo $dropbox;exit;
            
            $model->txt_nombre = $decodeDropbox['name'];         
            $model->txt_path = $decodeDropbox['path_display'];            
            

            if($model->save()){
                
                return $this->redirect(['localidades/view', 'id' => $idLoc]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'idLoc' => $idLoc,
            'idUser' => $idUser
        ]);
    }

    /**
     * Updates an existing WrkTareas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $response = new ResponseServices();
        $model = $this->findModel($id);
        //print_r($_POST);exit;
        if ($model->load(Yii::$app->request->post())){

            $localidad = EntLocalidades::find()->where(['id_localidad'=>$model->id_localidad])->one();

            $response->result['idT'] = $model->id_tarea;
            
            if($model->id_tipo ==  ConstantesWeb::TAREA_ARCHIVO){
                $fileDropbox = UploadedFile::getInstance($model, 'file');
                $response->message = "Archivo guardado.";
                $response->result['url'] = Html::a(' <i class="icon wb-attach-file" aria-hidden="true"></i>
                ', ['tareas/descargar', 'id' => $model->id_tarea,], ['target' => '_blank', 'class' => 'btn btn-success btn-outline no-pjax']);

                $dropbox = Dropbox::subirArchivo($localidad->txt_nombre, $fileDropbox);
                $decodeDropbox = json_decode(trim($dropbox), TRUE);
                //echo $dropbox;exit;
                
                //$model->txt_nombre = $decodeDropbox['name'];         
                $model->txt_path = $decodeDropbox['path_display'];
            }
            else if($model->id_tipo == ConstantesWeb::TAREA_ABIERTO){
                $response->message = "Tarea guardada.";
                $response->result['url'] = null;
            }
            $model->fch_actualizacion = date("Y-m-d H:i:s");
            if($model->save()){

                $response->status = "success";

                $userActual = Yii::$app->user->identity;
                $user = $model->usuario;
                $localidad = $model->localidad;

                // Enviar correo
                $utils = new Utils ();
                // Parametros para el email
                $parametrosEmail ['localidad'] = $localidad;
                $parametrosEmail ['tarea'] = $model->txt_nombre;
                $parametrosEmail ['user'] = $user->getNombreCompleto ();
                $parametrosEmail ['userActual'] = $userActual->getNombreCompleto ();
                $parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                    'localidades/index?token=' . $user->txt_token . '&tokenLoc=' . $localidad->txt_token
                ]);
                
                // Envio de correo electronico
                $utils->sendEmailCargaTareas( $user->txt_email,$parametrosEmail );
                    				
                

                //return $this->redirect(['localidades/view', 'id' => $localidad->id_localidad]);
            }else{
                $response->message= "No se actualizo el modelo.";
            }
        }
        return $response;
        /*return $this->render('update', [
            'model' => $model,
        ]);*/
    }

    /**
     * Deletes an existing WrkTareas model.
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
     * Finds the WrkTareas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WrkTareas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WrkTareas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDescargar($id){
        $tarea = WrkTareas::find()->where(['id_tarea'=>$id])->one();

        $dropbox = Dropbox::descargarArchivo($tarea->txt_path);
        $decodeDropbox = json_decode(trim($dropbox), TRUE);

        return $this->redirect($decodeDropbox['link']);
    }
    
    public function actionDescargarDesdeArchivada($id){
        $tarea = WrkTareasArchivadas::find()->where(['id_tarea'=>$id])->one();

        $dropbox = Dropbox::descargarArchivo($tarea->txt_path);
        $decodeDropbox = json_decode(trim($dropbox), TRUE);

        return $this->redirect($decodeDropbox['link']);
    }

    public function actionCompletarTarea($token=null){
        $response = new ResponseServices();
        $tarea = $this->findModel($token);
        $tarea->b_completa = 1;
        $tarea->save();

        $response->status = "success";
        $response->message = "Estatus de la tarea guardado";
        return $response;
    }

    public function actionDescompletarTarea($token=null){
        $response = new ResponseServices();
        $tarea = $this->findModel($token);
        $tarea->b_completa = 0;
        $tarea->save();

        $response->status = "success";
        $response->message = "Estatus de la tarea guardado";
        return $response;
    }

    public function actionCambiarNombre($id = null){
        $response = new ResponseServices();
        $tarea = $this->findModel($id);

        if(isset($_POST['nombre'])){
            if(!empty($_POST['nombre'])){
                if($tarea){
                    $tarea->txt_nombre = $_POST['nombre'];

                    if($tarea->save()){
                        $response->status = "success";
                        $response->message = "Estatus de la tarea guardado";
                    }else{
                        $response->result = $tarea->errors;
                    }
                }
            }
        }

        return $response;
    }

    public function actionEliminarTarea($id = null){
        $response = new ResponseServices();
        $tarea = $this->findModel($id);

        if($tarea->delete()){
            $response->status = "success";
            $response->message = "Tarea eliminada correctamente";
        }else{
            $response->result = $tarea->errors;
        }

        return $response;
    }
}
