<?php

namespace app\controllers;

use app\models\Svazusers;
use app\models\SvazusersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SvazController implements the CRUD actions for svazusers model.
 */
class SvazController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all svazusers models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SvazusersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single svazusers model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new svazusers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelupload = new svazusers();

        if ($this->request->isPost) {
            $modelupload->load($this->request->post());
            $modelupload->photo = UploadedFile::getInstance($modelupload, 'photo');
            $lastid = (new \yii\db\Query())
            ->select(['id'])
            ->from('svazusers')
            ->max('id');
            $lastid+=1;
            $modelupload->photo->saveAs('img/' . $lastid . '.jpg');
            $modelupload->photo=$lastid.'.jpg';
            if ($modelupload->save(false)) {
                return $this->redirect(['view', 'id' => $modelupload->id]);
            }
        } else {
            $modelupload->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $modelupload,
        ]);
    }

    /**
     * Updates an existing svazusers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->photo = UploadedFile::getInstance($model, 'photo');
            $model->photo->saveAs('img/' . $id . '.jpg');
            $model->photo=$id.'.jpg';
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);     
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing svazusers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the svazusers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return svazusers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = svazusers::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
