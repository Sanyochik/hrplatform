<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\questions */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список сезонов'), 'url' => ['index']];
if($_GET['create']=='seas'){
    $this->title = Yii::t('app', 'Добавление сезона');
}else{
    $seasonnow = (new \yii\db\Query())
    ->select(['name'])
    ->from('seasons')
    ->where(['id'=>$_GET['backid']])
    ->all();
    $this->title = Yii::t('app', 'Добавление вопроса');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $seasonnow[0]['name']), 'url' => ['questionview?id='.$_GET['backid']]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
