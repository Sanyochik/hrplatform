<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Authitem;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    $array = (new \yii\db\Query())
    ->select(['name'])
    ->from('auth_rule')
    ->all();
    $rules=array();
    foreach ($array as $key => $value) {
    	array_push($rules,$value["name"]);
    }
?>
<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'type') ?>
    <?= $form->field($model, 'description') ?>
    <?= $form->field($model, 'rule_name')->dropDownList([
        $rules
]);?>
	<br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
