<?php

namespace app\controllers;

use Yii;
use app\models\Svazusers;
use app\models\Svaz;
use app\models\SvazusersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\JPhpExcel;

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
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $searchModel = new SvazusersSearch();
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
     * Displays a single svazusers model.
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
    public function actionDownload(){
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $svazi = (new \yii\db\Query())
            ->select(['username','fi','type'])
            ->from('svaz')
            ->join('INNER JOIN', 'user','svaz.id_otv = user.id')
            ->join('INNER JOIN', 'svazusers','svaz.id_svaz = svazusers.id')
            ->all();
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8' );
            header(sprintf( 'Content-Disposition: attachment; filename=svazi_'.date( "d_m_Y" ).'.csv', date( 'dmY-His' ) ) );
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');

            $df = fopen( 'php://output', 'w' );
            fputs( $df, "\xEF\xBB\xBF" ); // UTF-8 BOM !!!!!
            $rows=array(
            );
            foreach ($svazi as $key => $value) {
                if($value['type']==0){
                    $value['type']='Не знает';
                }elseif($value['type']==1){
                    $value['type']='Знает';
                }else{
                    $value['type']='Работает';
                }
                array_push($rows, $value);
            }
            foreach ( $rows as $row ) {
                fputcsv( $df, $row,";" );
            }
            fclose($df);
            return $this->redirect('/web/svaz/index');
        }else{
            return $this->goHome();
        }
    }

    /**
     * Creates a new svazusers model.
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
        }else{
            return $this->goHome();
        }
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
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
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
        }else{
            return $this->goHome();
        }
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
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            svaz::deleteAll(['id_svaz' => $id]);
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }else{
            return $this->goHome();
        }
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
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            if (($model = svazusers::findOne(['id' => $id])) !== null) {
                return $model;
            }

            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }else{
            return $this->goHome();
        }
    }
}
