<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\data\SqlDataProvider;

$provider = new SqlDataProvider([
    'sql' => 'SELECT item_name,user_id,email,auth_assignment.created_at,group_id,username,name FROM auth_assignment RIGHT JOIN user ON auth_assignment.user_id=user.id RIGHT JOIN groups ON auth_assignment.group_id=groups.id',
    'sort' => [
        'attributes' => [
            'item_name',
            'username',
            'name',
            'email',
            'create_at',
        ],
    ],
]);
/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Добавление ролей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить роль пользователям'), ['create?id=1'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $provider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_name',
            'username',
            'name',
            'email',
            'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'item_name' => $model['item_name'],'name'=>'0','type'=>'0', 'user_id' => $model['user_id'],'id'=>'1']);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
