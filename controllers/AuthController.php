<?php

namespace app\controllers;

use Yii;
use app\models\AuthAssignment;
use app\models\AuthAssignmentSearch;
use app\models\Authitem;
use app\models\AuthitemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthController implements the CRUD actions for AuthAssignment model.
 */
class AuthController extends Controller
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
     * Lists all AuthAssignment models.
     *
     * @return string
     */
    public function actionIndex($id="")
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if($id=='1'){
                $searchModel = new AuthAssignmentSearch();
                $dataProvider = $searchModel->search($this->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }elseif($id=='2'){
                $searchModel = new AuthitemSearch();
                $dataProvider = $searchModel->search($this->request->queryParams);

                return $this->render('role', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        }else{
            return $this->goHome();
        }
    }

    /**
     * Displays a single AuthAssignment model.
     * @param string $item_name Item Name
     * @param string $user_id User ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($item_name, $user_id,$name,$type,$id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if($id=='1'){
                return $this->render('view', [
                    'model' => $this->findModelAssigment($item_name, $user_id),
                ]);
            }elseif($id=='2'){
                return $this->render('view', [
                    'model' => $this->findModelitem($name, $type),
                ]);
            }
        }else{
            return $this->goHome();
        }
    }

    /**
     * Creates a new AuthAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id="")
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if($id=='1'){
                $model = new AuthAssignment();
                if ($this->request->isPost) {
            // $id_user = (new \yii\db\Query())
            // ->select(['id'])
            // ->from('user')
            // ->where(['username'=>])
            // ->all();
            // $_POST['AuthAssignment'['user_id']] = $id_user->id;
                    if ($model->load($this->request->post()) && $model->save()) {
                        return $this->redirect(['view', 'item_name' => $model->item_name,'name'=>'0','type'=>'0', 'user_id' => $model->user_id,'id'=>$this->id]);
                    }
                } else {
                    $model->loadDefaultValues();
                }

                return $this->render('create', [
                    'model' => $model,
                ]);
            }elseif($id=='2'){
                $model = new Authitem();
                if ($this->request->isPost) {
                    if ($model->load($this->request->post()) && $model->save()) {
                        return $this->redirect(['view','item_name' => '0','name'=>$model->name,'type'=>$model->type, 'user_id' => '0','id'=>$this->id]);
                    }
                } else {
                    $model->loadDefaultValues();
                }

                return $this->render('createRole', [
                    'model' => $model,
                ]);
            }
        }else{
            return $this->goHome();
        }
    }

    /**
     * Updates an existing AuthAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $item_name Item Name
     * @param string $user_id User ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($item_name, $user_id,$name,$type,$id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if($id=='1'){
                $model = $this->findModelAssigment($item_name, $user_id);

                if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'item_name' => $model->item_name,'name' => '0', 'type' => '0', 'user_id' => $model->user_id,'id'=>1]);
                }

                return $this->render('update', [
                    'model' => $model,
                ]);
            }elseif($id=='2'){
                $model = $this->findModelitem($name, $type);

                if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'name' => $model->name,'item_name'=>'0','user_id'=>'0', 'type' => $model->type,'id'=>2]);
                }

                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else{
            return $this->goHome();
        }
    }

    /**
     * Deletes an existing AuthAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $item_name Item Name
     * @param string $user_id User ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($item_name, $user_id,$name,$type,$id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if($id==1){
                $this->findModelAssigment($item_name, $user_id)->delete();

                return $this->redirect(['index?id=1']);
            }elseif($id==2){
                $this->findModelitem($name, $type)->delete();

                return $this->redirect(['index?id=2']);
            }
        }else{
            return $this->goHome();
        }
    }

    /**
     * Finds the AuthAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $item_name Item Name
     * @param string $user_id User ID
     * @return AuthAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAssigment($item_name, $user_id)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if (($model = AuthAssignment::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) {
                return $model;
            }

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }else{
            return $this->goHome();
        }
    }
    protected function findModelitem($name, $type)
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if (($model = Authitem::findOne(['name' => $name, 'type' => $type])) !== null) {
                return $model;
            }

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }else{
            return $this->goHome();
        }
    }
}
