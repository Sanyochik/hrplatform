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
use PhpOffice\PhpSpreadsheet\IOFactory;

// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\TCPDF;

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
    // public function actionFix()
    // {
    //     $users = (new \yii\db\Query())
    //     ->select(['iduser'])
    //     ->from('svazusers')
    //     ->all();
    //     foreach ($users as $key => $value) {
    //         $svaz = new svaz();
    //         $svaz->id_otv = $value['iduser'];
    //         $svaz->id_svaz = 0;
    //         $svaz->type = 0;
    //         $svaz->save();
    //     }
    // }
    // public function actionFix2()
    // {
    //     $users = (new \yii\db\Query())
    //     ->select(['email','id'])
    //     ->from('user')
    //     ->all();
    //     foreach ($users as $key => $value) {
    //         if(($value['id']==1)||($value['id']==8)||($value['id']==36)){
    //         }else{
    //             svazusers::updateAll(['iduser' => $value['id']], ['like', 'email', $value['email']]);
    //         }
    //     }
    // }
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
    public function actionDiagramm()
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            return $this->render('diagramm'); 
        }else{
            return $this->goHome();
        }
    }
    public function actionConvertdiagramm()
    {
        $user_role = (new \yii\db\Query())
        ->select(['item_name'])
        ->from('auth_assignment')
        ->where(['user_id'=>Yii::$app->user->getId()])
        ->all();
        if($user_role[0]['item_name']=='admin'){
            $i=1;
            $socialarray = (new \yii\db\Query())
            ->select(['id'])
            ->from('questions')
            ->where(['descr'=>0,'invert'=>0,'season'=>$_GET['season']])
            ->all();
            $invertive = (new \yii\db\Query())
            ->select(['id'])
            ->from('questions')
            ->where(['invert'=>1,'season'=>$_GET['season']])
            ->all();
            $socialquestionsall = (new \yii\db\Query())
            ->select(['id'])
            ->from('questions')
            ->where(['descr'=>0,'season'=>$_GET['season']])
            ->all();
            $unsocialarray = (new \yii\db\Query())
            ->select(['id'])
            ->from('questions')
            ->where(['descr'=>1,'invert'=>0,'season'=>$_GET['season']])
            ->all();
            $allquestions = (new \yii\db\Query())
            ->select(['id'])
            ->from('questions')
            ->where(['!=','descr',3])
            ->andwhere(['season'=>$_GET['season']])
            ->all();
            $allusers = (new \yii\db\Query())
            ->select(['id','email','fi','departamentid'])
            ->from('svazusers')
            ->all();
            $newsheet = new Spreadsheet();
            $sheet = $newsheet->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->setCellValue('A1', 'ФИО');
            $sheet->setCellValue('B1', 'Отдел');
            $sheet->setCellValue('C1', 'Соц');
            $sheet->setCellValue('D1', 'Произв');
            foreach ($allusers as $key=>$value){
                $i+=1;
                $departament = (new \yii\db\Query())
                ->select(['name'])
                ->from('departament')
                ->where(['id'=>$value['departamentid']])
                ->all();
                $social = (new \yii\db\Query())
                ->select(['otvet','id_question'])
                ->from('answers')
                ->join('left JOIN', 'questions','answers.id_question = questions.id')
                ->where(['id_about'=>$value['id'],'skip'=>null,'descr'=>0,'invert'=>0,'season'=>$_GET['season']])
                ->all();
                $unsocial = (new \yii\db\Query())
                ->select(['otvet','id_question'])
                ->from('answers')
                ->join('left JOIN', 'questions','answers.id_question = questions.id')
                ->where(['id_about'=>$value['id'],'skip'=>null,'descr'=>1,'season'=>$_GET['season']])
                ->all();
                $socialinvert = (new \yii\db\Query())
                ->select(['otvet','id_question'])
                ->from('answers')
                ->join('left JOIN', 'questions','answers.id_question = questions.id')
                ->where(['id_about'=>$value['id'],'skip'=>null,'invert'=>1,'descr'=>0,'season'=>$_GET['season']])
                ->all();
                $unsocialinvert = (new \yii\db\Query())
                 ->select(['otvet','id_question'])
                 ->from('answers')
                 ->join('left JOIN', 'questions','answers.id_question = questions.id')
                 ->where(['id_about'=>$value['id'],'skip'=>null,'invert'=>1,'descr'=>1,'season'=>$_GET['season']])
                 ->all();
                $count = 0;
            foreach($socialinvert as $keys => $values){
                if($values['otvet']==1){
                     $socialinvert[$count]['otvet']=5;
                 }
                 if($values['otvet']==2){
                     $socialinvert[$count]['otvet']=4;
                 }
                 if($values['otvet']==3){
                     $socialinvert[$count]['otvet']=3;
                 }
                 if($values['otvet']==4){
                     $socialinvert[$count]['otvet']=2;
                 }
                 if($values['otvet']==5){
                     $socialinvert[$count]['otvet']=1;
                 }
                 $count+=1;
             }
             $count = 0;
             foreach($unsocialinvert as $keys => $values){
                if($values['otvet']==1){
                 $unsocialinvert[$count]['otvet']=5;
             }
             if($values['otvet']==2){
                 $unsocialinvert[$count]['otvet']=4;
             }
             if($values['otvet']==3){
                 $unsocialinvert[$count]['otvet']=3;
             }
             if($values['otvet']==4){
                 $unsocialinvert[$count]['otvet']=2;
             }
             if($values['otvet']==5){
                 $unsocialinvert[$count]['otvet']=1;
             }
             $count+=1;
         }
         $unsocialtotal = count($unsocialinvert) + count($unsocial);
         $socialtotal = count($socialinvert) + count($social);
         $unsocial = (new \yii\db\Query())
         ->select(['otvet','id_question'])
         ->from('answers')
         ->join('left JOIN', 'questions','answers.id_question = questions.id')
         ->where(['id_about'=>$value['id'],'skip'=>null,'descr'=>1,'season'=>$_GET['season']])
         ->all();
         $socialresult = array_sum(array_column($social, 'otvet'));
         $socialinvertresult = array_sum(array_column($socialinvert, 'otvet'));
         $unsocialresult = array_sum(array_column($unsocial, 'otvet'));
         $unsocialinvertresult = array_sum(array_column($unsocialinvert, 'otvet'));
         $socialtotalscore = $socialinvertresult + $socialresult;
         $unsocialinvertresult = $unsocialinvertresult + $unsocialresult;
         if($socialtotal != 0){
            $socialtotalscore = $socialtotalscore/$socialtotal;
            $unsocialresult = $unsocialresult/$unsocialtotal;
        }else{
            $socialtotalscore = 0;
            $unsocialresult = 0;
        }
        $socialround=round($socialtotalscore,1);
        $unsocialround=round($unsocialresult,1);
        $sheet->setCellValue('A'.$i, $value['fi']);
        if(isset($departament[0]['name'])){
            $sheet->setCellValue('B'.$i, $departament[0]['name']);
        }
        $sheet->setCellValue('C'.$i, $socialround);
        $sheet->setCellValue('D'.$i, $unsocialround);
    }
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($newsheet, "Xlsx");
    $writer->save("../web/result.xlsx");
    echo 'Файл сформирован, скачивание начнётся автоматический, если такое не произошло, перейдите по ссылке:<a href="../web/result.xlsx">ссылка на скачивание</a><br><a href="/web/svaz/diagramm?questions=1&questions1=1&questions4=4&questions6=6&questions12=12&questions2=2&questions3=3&questions5=5&questions7=7&questions9=9&questions10=10">Вернуться обратно к диаграмме</a>';
            echo'<script>
            window.location.href = "/web/result.xlsx";
            </script>';
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
