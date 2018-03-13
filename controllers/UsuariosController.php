<?php

namespace app\controllers;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;
use app\models\UsuariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\ModUsuarios\models\Utils;
use app\models\AuthItem;
use app\models\WrkUsuarioUsuarios;
use app\models\ConstantesWeb;
use app\models\CatPorcentajeRentaAbogados;
use app\models\ResponseServices;

/**
 * UsuariosController implements the CRUD actions for EntUsuarios model.
 */
class UsuariosController extends Controller
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
     * Lists all EntUsuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuario = EntUsuarios::getIdentity();

        $auth = Yii::$app->authManager;

        $hijos = $auth->getChildRoles($usuario->txt_auth_item);
        ksort($hijos);
        $roles = AuthItem::find()->where(['in', 'name', array_keys($hijos)])->orderBy("name")->all();

        $searchModel = new UsuariosSearch();
        $searchModel->txt_auth_item = array_keys($hijos);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roles' => $roles
        ]);
    }

    /**
     * Displays a single EntUsuarios model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EntUsuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $usuario = EntUsuarios::getIdentity();//exit;

        $auth = Yii::$app->authManager;

        $hijos = $auth->getChildRoles($usuario->txt_auth_item);
        ksort($hijos);
        $roles = AuthItem::find()->where(['in', 'name', array_keys($hijos)])->orderBy("name")->all();

        $model = new EntUsuarios([
            'scenario' => 'registerInput'
        ]);
        $usuariosClientes = EntUsuarios::find()->where(['txt_auth_item'=>ConstantesWeb::CLIENTE])->all();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $grupo = null;
        $padre = null;
        if ($model->load(Yii::$app->request->post())){
            
            if($model->txt_auth_item == ConstantesWeb::CLIENTE){
                $grupo = $usuario;
            }
            if($model->txt_auth_item == ConstantesWeb::COLABORADOR){
                $model->usuarioPadre = $usuario->id_usuario;
                $padre = $model->usuarioPadre; 
            }
            
            if ($user = $model->signup()) {

                if($model->txt_auth_item == ConstantesWeb::ABOGADO){
                    $porcentajeRenta = new CatPorcentajeRentaAbogados();
                    $porcentajeRenta->id_usuario = $user->id_usuario;
                    $porcentajeRenta->num_porcentaje = 10;
                    $porcentajeRenta->save();
                }

                $relUsuarios = new WrkUsuarioUsuarios();

                if($grupo){
                    $relUsuarios->id_usuario_padre = $grupo->id_usuario;
                    $relUsuarios->id_usuario_hijo = $user->id_usuario;
                }else{
                    $relUsuarios->id_usuario_padre = $padre;
                    $relUsuarios->id_usuario_hijo = $user->id_usuario;
                }

                if (Yii::$app->params ['modUsuarios'] ['mandarCorreoActivacion']) {
                    // Enviar correo
					$utils = new Utils ();
					// Parametros para el email
					$parametrosEmail ['user'] = $user->getNombreCompleto();
					//$parametrosEmail ['abogado'] = $abogado->getNombreCompleto();
					$parametrosEmail ['url'] = Yii::$app->urlManager->createAbsoluteUrl([ 
                        'usuarios/cambiar-pass/?token=' . $user->txt_token
                    ]);
					
					// Envio de correo electronico
                    $utils->sendEmailCambiarPass( $user->txt_email,$parametrosEmail );
                }

                if($relUsuarios->save()){

                    return $this->redirect(['usuarios/index']);
                }else{
                    return $this->redirect(['usuarios/index']);
                }
            }else{
                //print_r($user->errors);exit;
                echo "No guardo modelo";exit;
            }
        
        // return $this->redirect(['view', 'id' => $model->id_usuario]);
        }
        //return $this->redirect(['index']);
        return $this->render('create', [
            'usuario' => $usuario,
            'model' => $model,
            'roles'=>$roles,
            'usuariosClientes' => $usuariosClientes
        ]);
    }

    /**
     * Updates an existing EntUsuarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $usuario = EntUsuarios::getIdentity();

        $auth = Yii::$app->authManager;

        $hijos = $auth->getChildRoles($usuario->txt_auth_item);
        ksort($hijos);
        $roles = AuthItem::find()->where(['in', 'name', array_keys($hijos)])->orderBy("name")->all();

        $model = $this->findModel($id);
        
        //var_dump($_POST["EntUsuarios"]['password']);exit;

        if ($model->load(Yii::$app->request->post())){
            if(isset($_POST["EntUsuarios"]['password'])){
                $model->setPassword($_POST["EntUsuarios"]['password']);
                $model->generateAuthKey();
            }
            if($model->save()){
                
                return $this->redirect(['view', 'id' => $model->id_usuario]);
            }
        }else{
            $model->scenario = 'updateModel';
            return $this->render('update', [
                'model' => $model,
                'roles'=>$roles
            ]);
        }
    }

    /**
     * Deletes an existing EntUsuarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EntUsuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntUsuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntUsuarios::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBloquearUsuario($token=null){
        $respuesta = new ResponseServices();

        $usuario = $this->findModel(['txt_token'=>$token]);

        $usuario->id_status = EntUsuarios::STATUS_BLOCKED;
        if($usuario->save()){
            $respuesta->status = "success";
            $respuesta->message= "Usuario bloqeado";
        }else{
            $respuesta->status = "error";
            $respuesta->message = "No se pudo bloquear al usuario";
            $respuesta->result = $usuario->errors;
        }
        return $respuesta;
    }

    public function actionActivarUsuario($token=null){
        $respuesta = new ResponseServices();

        $usuario = $this->findModel(['txt_token'=>$token]);

        $usuario->id_status = EntUsuarios::STATUS_ACTIVED;
        if($usuario->save()){
            $respuesta->status = "success";
            $respuesta->message= "Usuario activado";
        }else{
            $respuesta->status = "error";
            $respuesta->message = "No se pudo activar al usuario";
            $respuesta->result = $usuario->errors;
        }

        return $respuesta;
    }

    public function actionCambiarPass($token = null){
        if($token){
            $user = EntUsuarios::find()->where(['txt_token'=>$token])->one();
            Yii::$app->getUser()->login($user);
            
            if($user->txt_password_hash){

                return $this->redirect(['localidades/index']);
            }

            if ($user->load(Yii::$app->request->post())){
                if(isset($_POST["EntUsuarios"]['password'])){
                    $user->setPassword($_POST["EntUsuarios"]['password']);
                    $user->generateAuthKey();
                }
                if($user->save()){
                    
                    return $this->redirect(['usuarios/index']);
                }
            }else{
                $user->scenario = 'cambiarPass';
                return $this->render('cambio-pass', [
                    'model' => $user
                ]);
            }
            
        }
    }
}
