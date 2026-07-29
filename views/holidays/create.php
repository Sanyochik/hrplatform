<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Holidays $model */

$this->title = 'Добавить праздник';
?>
<div class="holidays-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
