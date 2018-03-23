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
                $templateItem = '<li class="list-group-item" data-tareakey="'.$model->id_tarea.'">                        
                                    <div class="w-full">
                                        <div class="row row-no-border">
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <div class="checkbox-custom checkbox-success">
                                                    <input type="checkbox" name="checkbox">
                                                        <label class="task-title">'.$model->txt_nombre.'</label>
                                                </div>
                                            </div>
                                            <div class="col-xs-2 col-sm-2 col-md-2 text-right">
                                                <select multiple="multiple" class="plugin-selective-tareas" data-localidad="'.$model->id_localidad.'" data-id="'.$model->id_tarea.'" data-json="[]"/>
                                            </div>                                       
                                        </div>
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())){

            $localidad = EntLocalidades::find()->where(['id_localidad'=>$_POST["WrkTareas"]["id_localidad"]])->one();
            
            if($model->id_tipo == 1){
                $fileDropbox = UploadedFile::getInstance($model, 'file');

                $dropbox = Dropbox::subirArchivo($localidad->txt_nombre, $fileDropbox);
                $decodeDropbox = json_decode(trim($dropbox), TRUE);
                //echo $dropbox;exit;
                
                $model->txt_nombre = $decodeDropbox['name'];         
                $model->txt_path = $decodeDropbox['path_display'];
            }

            if($model->save()){

                $userActual = Yii::$app->user->identity;
                $user = $model->usuario;
                $localidad = $model->localidad;

                if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {

					// Enviar correo
					$utils = new Utils ();
					// Parametros para el email
					$parametrosEmail ['localidad'] = $localidad->txt_nombre;
					$parametrosEmail ['tarea'] = $model->txt_nombre;
                    $parametrosEmail ['user'] = $user->getNombreCompleto ();
                    $parametrosEmail ['userActual'] = $userActual->getNombreCompleto ();
                    $parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                        'localidades/view?id'.$model->id_localidad.'/?token=' . $user->txt_token
                    ]);
					
					// Envio de correo electronico
                    $utils->sendEmailCargaTareas( $user->txt_email,$parametrosEmail );
                    				
                }

                return $this->redirect(['localidades/view', 'id' => $localidad->id_localidad]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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

        print_r($decodeDropbox);
        exit;

        return $this->redirect($decodeDropbox['link']);
    }
}
