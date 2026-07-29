<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */

$this->title = Yii::t('app', 'Добавить новую роль');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Добавление ролей'), 'url' => ['index?id=2']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formrole', [
        'model' => $model,
    ]) ?>

</div>
