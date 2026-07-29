<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
$array = (new \yii\db\Query())
    ->select(['username','user.id'])
    ->from('user')
    ->join('left join','groups', 'user.id = groups.id_otv')
    ->where(['groups.id_otv'=> null])
    ->all();
    $users=array();
    foreach ($array as $key => $value) {
        $users+=[$value["id"]=>$value["username"]];
    }
?>

<div class="groups-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'id_otv')->dropDownList(
        $users
    );?>

    <div class="form-group" style="margin-top: 10px;">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
