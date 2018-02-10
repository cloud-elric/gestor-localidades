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
    public function actionIndex()
    {
        $searchModel = new EntLocalidadesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $relUserLoc = new WrkUsuariosLocalidades();
        $userRel = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$id])->all();
        $idUsersRel = WrkUsuariosLocalidades::find()->where(['id_localidad'=>$id])->select('id_usuario')->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'relUserLoc' => $relUserLoc,
            'userRel' => $userRel,
            'idUsersRel' => $idUsersRel
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

        if ($model->load(Yii::$app->request->post())){
            $model->id_usuario = Yii::$app->user->identity->id_usuario; 
            $model->txt_token = Utils::generateToken('tok');

            $model->fch_vencimiento_contratro = Utils::changeFormatDateInput($model->fch_vencimiento_contratro);
            $model->fch_asignacion = Utils::changeFormatDateInput($model->fch_asignacion);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id_localidad]);
            }

            

        }

        return $this->render('create', [
            'model' => $model,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_localidad]);
        }

        return $this->render('update', [
            'model' => $model,
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
        if(isset($_POST['WrkUsuariosLocalidades']['id_usuario']) && isset($_POST['WrkUsuariosLocalidades']['id_localidad']) ){
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
        }
    }
}
