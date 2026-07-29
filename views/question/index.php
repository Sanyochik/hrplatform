<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\questionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$activenow = (new \yii\db\Query())
->select(['id'])
->from('seasons')
->where(['activity'=>1])
->all();
$this->title = Yii::t('app', 'Список сезонов');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить сезон'), ['create?create=seas'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {link}',
                'buttons' => [
                    'link' => function ($url,$model,$activenow) {
                        $activenow = (new \yii\db\Query())
                        ->select(['id'])
                        ->from('seasons')
                        ->where(['activity'=>1])
                        ->all();
                        if($model->id != $activenow[0]['id']){
                            return Html::a('Сделать активным', 'changeactiv?nowactiv='.$activenow[0]['id'].'&id='.$model->id);
                        }else{
                            return Html::label('Активна','');
                        }
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
