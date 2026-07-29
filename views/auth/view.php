<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */

if($_GET['id']=='1'){
    $array = (new \yii\db\Query())
    ->select(['username'])
    ->from('user')
    ->where(['id'=>$model->user_id])
    ->one();
    $group = (new \yii\db\Query())
    ->select(['name'])
    ->from('groups')
    ->where(['id'=>$model->group_id])
    ->one();
    $this->title = $array['username'];
}elseif($_GET['id']=='2'){
    $this->title = $model->name; 
}
if($_GET["id"]==1){
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список пользователей'), 'url' => ['index?id='.$_GET["id"].'']];
}elseif($_GET["id"]==2){
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Список ролей'), 'url' => ['index?id='.$_GET["id"].'']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="auth-assignment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($_GET['id']=='1'){ ?>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'item_name' => $model->item_name, 'user_id' => $model->user_id,'name'=>0, 'type' => 0,'id'=>1], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'item_name' => $model->item_name, 'user_id' => $model->user_id,'name'=>0, 'type' => 0,'id'=>1], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить данного пользователя?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php }elseif($_GET['id']=='2'){?>
        <?= Html::a(Yii::t('app', 'Измеить'), ['update', 'name' => $model->name, 'type' => $model->type,'item_name' => 0, 'user_id' => 0,'id'=>2], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'name' => $model->name, 'type' => $model->type,'item_name' => 0, 'user_id' => 0,'id'=>2], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить данного пользователя?'),
                'method' => 'post',
            ],
        ]) ?>
        <?php }?>
    </p>
    <?php if($_GET['id']=='1'){ ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_name',
            [
                'label'=>'Фамилия Имя',
                'value'=>$array['username'],
            ],
            [
                'label'=>'Группа',
                'value'=>$group['name'],
            ],
            'created_at',
        ],
    ]) ?>
    <?php }elseif($_GET['id']=='2'){?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'type',
            'description',
        ],
    ]) ?>
    <?php }?>
</div>
