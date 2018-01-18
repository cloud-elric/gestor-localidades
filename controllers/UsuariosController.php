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
        $usuario = EntUsuarios::getIdentity();

        $auth = Yii::$app->authManager;

        $hijos = $auth->getChildRoles($usuario->txt_auth_item);
        ksort($hijos);
        $roles = AuthItem::find()->where(['in', 'name', array_keys($hijos)])->orderBy("name")->all();

        $model = new EntUsuarios([
            'scenario' => 'registerInput'
        ]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($user = $model->signup()) {

                if (Yii::$app->params['modUsuarios']['mandarCorreoActivacion']) {

                    $activacion = new EntUsuariosActivacion();
                    $activacion->saveUsuarioActivacion($user->id_usuario);
                
                // Enviar correo de activación
                    $utils = new Utils();
                // Parametros para el email
                    $parametrosEmail['url'] = Yii::$app->urlManager->createAbsoluteUrl([
                        'activar-cuenta/' . $activacion->txt_token
                    ]);
                    $parametrosEmail['user'] = $user->getNombreCompleto();
                
                // Envio de correo electronico
                    $utils->sendEmailActivacion($user->txt_email, $parametrosEmail);
                    $this->redirect([
                        'login'
                    ]);
                } else {

                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
                }
            }
        
        // return $this->redirect(['view', 'id' => $model->id_usuario]);
        }
        return $this->render('create', [
            'model' => $model,
            'roles'=>$roles
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_usuario]);
        } else {
            return $this->render('update', [
                'model' => $model,
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
}
