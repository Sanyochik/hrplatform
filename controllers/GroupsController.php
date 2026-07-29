<?php

namespace app\controllers;

use Yii;
use app\models\Groups;
use app\models\GroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
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
     * Lists all Groups models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $searchModel = new GroupsSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->goHome();
        }
    }

    /**
     * Displays a single Groups model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }else{
            return $this->goHome();
        }
    }

    /**
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $model = new Groups();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }else{
            return $this->goHome();
        }
    }

    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $model = $this->findModel($id);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }else{
            return $this->goHome();
        }
    }

    /**
     * Deletes an existing Groups model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }else{
            return $this->goHome();
        }
    }

    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if (($model = Groups::findOne(['id' => $id])) !== null) {
                return $model;
            }

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }else{
            return $this->goHome();
        }
    }
}
