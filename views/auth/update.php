<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
if($_GET['id']=='1'){
$array = (new \yii\db\Query())
    ->select(['username'])
    ->from('user')
    ->where(['id'=>$model->user_id])
    ->one();
$this->title = Yii::t('app', 'Изменение пользователя '.$array["username"].'', [
    'name' => $model->item_name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Добавление ролей'), 'url' => ['index?id=1']];
$this->params['breadcrumbs'][] = ['label' => $array["username"], 'url' => ['view', 'item_name' => $model->item_name,'name' => '0', 'type' => '0', 'user_id' => $model->user_id,'id'=>1]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="auth-assignment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php 
}elseif($_GET['id']=='2'){
$this->title = Yii::t('app', 'Изменение роли {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список ролей'), 'url' => ['index?id=2']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'name' => $model->name,'item_name' => '0','user_id' => '0', 'type' => $model->type,'id'=>2]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="auth-assignment-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formrole', [
        'model' => $model,
    ]) ?>

</div>
<?php }?>
