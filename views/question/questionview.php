<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\questions */

$this->title = $model->id;
$seasonnow = (new \yii\db\Query())
->select(['name'])
->from('seasons')
->where(['id'=>$_GET['backid']])
->all();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список сезонов'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $seasonnow[0]['name']), 'url' => ['view?id='.$_GET['backid']]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="questions-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить данный вопрос?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'label:ntext',
        ],
    ]) ?>

</div>
