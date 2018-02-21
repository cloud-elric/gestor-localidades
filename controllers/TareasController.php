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

    /**
     * Creates a new WrkTareas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idLoc)
    {
        $idUser = Yii::$app->user->identity->id_usuario;
        $model = new WrkTareas();

        if ($model->load(Yii::$app->request->post())){

            /*$fileDropbox = UploadedFile::getInstance($model, 'file');

            $dropbox = Dropbox::subirArchivo($fileDropbox);
            $decodeDropbox = json_decode(trim($dropbox), TRUE);
            //echo $dropbox;exit;
            
            $model->txt_nombre = $decodeDropbox['name'];         
            $model->txt_path = $decodeDropbox['path_display'];            
            */


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
        var_dump($_POST);exit;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()){

            $fileDropbox = UploadedFile::getInstance($model, 'file');

            $dropbox = Dropbox::subirArchivo($fileDropbox);
            $decodeDropbox = json_decode(trim($dropbox), TRUE);
            //echo $dropbox;exit;
            
            $model->txt_nombre = $decodeDropbox['name'];         
            $model->txt_path = $decodeDropbox['path_display'];

            return $this->redirect(['view', 'id' => $model->id_tarea]);
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

        return $this->redirect($decodeDropbox['link']);
    }
}
