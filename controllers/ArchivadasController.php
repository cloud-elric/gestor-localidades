<?php

namespace app\controllers;

use Yii;
use app\models\EntLocalidadesArchivadas;
use app\models\EntLocalidadesArchivadasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
}
