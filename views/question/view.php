<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\questions */
$seasonnow = (new \yii\db\Query())
->select(['name'])
->from('seasons')
->where(['id'=>$_GET['id']])
->all();
$this->title = $seasonnow[0]['name'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список сезонов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <p>
        <?= Html::a(Yii::t('app', 'Добавить вопрос'), ['create?create=quest&backid='.$_GET['id']], ['class' => 'btn btn-success']) ?>
    </p>
<div class="questions-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'label:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute(['questview', 'id' => $model->id,'backid'=>$_GET['id']]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
