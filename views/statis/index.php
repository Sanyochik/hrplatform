<?php

use app\models\Answers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\Answerssearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статистика по ответам';
?>
<div class="answers-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="buttons-my">
        <a class="nonedecorate" href="?verybad=1">
            <div class="my-button
            <?php if(isset($_GET['verybad'])){
                echo'checked';
            }?>
            ">Злостные нарушители</div>
        </a>
        <a class="nonedecorate" href="?badall=1">
            <div class="my-button
            <?php if(isset($_GET['badall'])){
                echo'checked';
            }?>
            ">Нарушители</div>
        </a>
        <a class="nonedecorate" href="?bad=1">
            <div class="my-button
            <?php if(isset($_GET['bad'])){
                echo'checked';
            }?>
            ">Не оценили колег</div>
        </a>
        <a class="nonedecorate" href="?notbad=1">
            <div class="my-button
            <?php if(isset($_GET['notbad'])){
                echo'checked';
            }?>
            ">Не оценили 10 колег</div>
        </a>
        <a class="nonedecorate" href="?good=1">
            <div class="my-button
            <?php if(isset($_GET['good'])){
                echo'checked';
            }?>
            ">Выполнили всё</div>
        </a>
        <a class="nonedecorate" href="/web/statis/index">
            <div class="my-button
            <?php if(empty($_GET)){
                echo'checked';
            }?>
            ">Все пользователи</div>
        </a>
    </div>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'urlCreator' => function ($action, $model) {
                    return Url::toRoute([$action='/svaz/view?id='.$model['id']]);
                },
            ],
            'username',
            'countsvaz',
            'countall',
            'skips',
            'countanswerd',
            'answererdbyother',
            'ignored',
        ],
    ]); ?>

    <?php Pjax::end(); ?>
    <form style="margin-top:-45px;float:right;" method="GET">
        <input type="submit" name="rowscount" value="100">
        <input type="submit" name="rowscount" value="75">
        <input type="submit" name="rowscount" value="50">
        <input type="submit" name="rowscount" value="25">
    </form>

</div>
