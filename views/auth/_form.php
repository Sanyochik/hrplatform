<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\AuthAssignment;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
	$array = (new \yii\db\Query())
    ->select(['email','id'])
    ->from('user')
    ->all();
    $arrayforroles = (new \yii\db\Query())
    ->select(['name'])
    ->from('auth_item')
    ->all();
    $emails=array();
    $roles=array();
    $arrayforgroups = (new \yii\db\Query())
    ->select(['id','name'])
    ->from('groups')
    ->all();
    $groups=array();
    $emails=array();
    $roles=array();
    foreach ($arrayforgroups as $key => $value) {
        $groups+=[$value["id"]=>$value["name"]];
    }
    foreach ($arrayforroles as $key => $value) {
    	$roles+=[$value["name"]=>$value["name"]];
    }
    foreach ($array as $key => $value) {
    	$emails+=[$value["id"]=>$value["email"]];
    }
?>
<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'item_name')->dropDownList(
    	$roles
    ); ?>
    <?= $form->field($model, 'user_id')->dropDownList(
    	$emails
    );?>
    <?= $form->field($model, 'group_id')->dropDownList(
        $groups
    );?>
	<br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
