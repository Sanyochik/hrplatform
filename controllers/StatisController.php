<?php

namespace app\controllers;

use Yii;
use app\models\Answers;
use app\models\Answerssearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StatisController implements the CRUD actions for Answers model.
 */
class StatisController extends Controller
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
     * Lists all Answers models.
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
            $searchModel = new Answerssearch();
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
     * Displays a single Answers model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Finds the Answers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Answers the loaded model
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
            if (($model = Answers::findOne(['id' => $id])) !== null) {
                return $model;
            }

            throw new NotFoundHttpException('The requested page does not exist.');
        }else{
            return $this->goHome();
        }
    }
}
