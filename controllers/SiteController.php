<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\AuthAssignment;
use app\models\Svaz;
use app\models\Answers;
use app\models\User;
use app\models\Rest;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
             return $this->render('index');
        }
    }
    public function actionCabinet()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
             return $this->render('cabinet');
        }
    }
    public function actionUnique()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
             return $this->render('unique');
        }
    }
    public function actionNewbie()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
             return $this->render('newbie');
        }
    }
    public function actionHoliday()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
             return $this->render('holiday');
        }
    }
    public function actionFoget(){
        $model = new user();
        if ($model->load(Yii::$app->request->post())){
            $current =Yii::$app->request->post();
            $userinfo = (new \yii\db\Query())
            ->select(['auth_key','email'])
            ->from('user')
            ->where(['email'=>$current['User']['email']])
            ->all();
            if($userinfo){
                $user = new user();
                $user->email = $userinfo[0]['email'];
                $user->auth_key = $userinfo[0]['auth_key'];
                require_once('../models/site/mailsender.php');
                return $this->redirect('/web/site/index');
            }else{
                return $this->redirect('/web/site/foget?error=1');
            }
        }
        return $this->render('foget', [
                'model' => $model,
            ]);
    }
    public function actionAbout()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
            $user_role = (new \yii\db\Query())
            ->select(['item_name'])
            ->from('auth_assignment')
            ->where(['user_id'=>Yii::$app->user->getId()])
            ->all();
            if($user_role[0]['item_name']=='admin'){
               return $this->render('about');
            }else{
                return $this->redirect('/web/site/index');
            }
        }
    }
    public function actionSelect()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
            return $this->render('select');   
        } 
    }
    public function actionNewsvaz()
    {
        $svaz = new svaz();
        $svaz->id_otv = $_POST['from'];
        $svaz->id_svaz = $_POST['to'];
        $svaz->type = $_POST['submit-button'];
        $svaz->save();
        return $this->redirect('/web/site/select?lastid='.$svaz->id_svazi.'');
    }
    public function actionDelsvaz($lastid)
    {
        $svaz = new svaz();
        $svaz = svaz::findOne($lastid);
        $svaz->delete();
        return $this->redirect('/web/site/select?actualid='.$lastid.'');
    }
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $newuser = (new \yii\db\Query())
            ->select(['email'])
            ->from('svazusers')
            ->where(['email'=>$_POST['SignupForm']['email']])
            ->all();
            if($newuser){
                if ($user = $model->signup()) {
                    if (Yii::$app->getUser()->login($user)) {
                        $anothermodel = new AuthAssignment();
                        $anothermodel->item_name = 'worker';
                        $anothermodel->user_id =Yii::$app->user->getId();
                        $anothermodel->group_id = 1;
                        $anothermodel->save();
                        $anothernewmodel = new svaz();
                        $anothernewmodel->id_otv = Yii::$app->user->getId();
                        $anothernewmodel->id_svaz = 0;
                        $anothernewmodel->type = 0;
                        $anothernewmodel->save();
                        return $this->redirect('/web/site/index');
                    }
                }
            }else{
                return $this->redirect('?error=1');
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    /*public function actionLogin($authkey,$email)
    {
      if(!Yii::$app->user->isGuest)
       {
        return $this->goHome();
       }

        if((isset($_GET['authkey']))&&(isset($_GET['email']))){
            $user = new User();
            $user = user::findOne(['auth_key'=>$authkey,'email'=>$email]);
            Yii::$app->getUser()->login($user,3600*24*30);
            return $this->redirect('/web/site/index');
        }
    }*/

    /**
     * Logout action.
     *
     * @return Response
     */
     public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        return $this->goHome();
    }

    public function actionNewrest()
    {
        if(isset($_GET)){
            print_r($_GET);
            $model = new Rest();
            $model->user_id = $_GET['creator'];
            $model->rest_at =$_GET['date'];
            $model->days = $_GET['days'];
            $model->status = "Created";
            $model->save();
            return $this->redirect('/web/site/cabinet');
        }
        return $this->redirect('/web/site/cabinet');
    }

    public function actionSkip($who)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
            $modelsave = new Answers();
            $modelsave->id_creator = Yii::$app->user->getId();
            $modelsave->id_question = '1';
            $modelsave->id_about = $who;
            $modelsave->triger = 0;
            $modelsave->skip = 1;
            $modelsave->save();
            return $this->redirect('/web/site/contact');
        }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/web/site/signup');
        }else{
            if(!isset($_SESSION['count'])){
                $_SESSION['count']=0;
            }
        // $user_role = (new \yii\db\Query())
        // ->select(['item_name'])
        // ->from('auth_assignment')
        // ->where(['user_id'=>Yii::$app->user->getId()])
        // ->all();
        // if($user_role[0]['item_name']=='admin'){
            $model = new Answers();
            if ($this->request->isPost) {
                unset($_POST['_csrf']);
                $about = $_POST['to'];
                if(isset($_POST["Answers"]["triger"])){
                    $triger = $_POST["Answers"]["triger"];
                    $triger+=1;
                }
                unset($_POST['to']);
                foreach ($_POST as $key => $value) {
                    $modelsave = new Answers();
                    $modelsave->id_creator = Yii::$app->user->getId();
                    $modelsave->id_question = $key;
                    $modelsave->id_about = $about;
                    $modelsave->otvet = $value;
                    if(isset($triger)){
                        $modelsave->triger = $triger;
                    }else{
                        $modelsave->triger = 1;
                    }
                    $modelsave->save();
                }
                if(!isset($_SESSION['nowday'])){
                    $_SESSION['nowday']=mktime(0, 0, 0, date("m"),   date("d"),   date("Y"));
                }else{
                    if($_SESSION['nowday']!=mktime(0, 0, 0, date("m"),   date("d"),   date("Y"))){
                        $_SESSION['nowday']=mktime(0, 0, 0, date("m"),   date("d"),   date("Y"));
                        $_SESSION['count']=0;
                    }
                }
                $_SESSION['count']+=1;
            return $this->redirect('/web/site/contact');
            }
            return $this->render('contact', [
                'model' => $model,
            ]);
        // }else{
        //     return $this->goHome();
        // }
        }
    }

    /**
     * Displays about page.
     *
     * @return string
     */
}
