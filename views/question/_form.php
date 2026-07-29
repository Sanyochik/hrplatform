<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\questions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questions-form">

    <?php $form = ActiveForm::begin();  if($_GET['create']=='seas'):?>
        <?= $form->field($model, 'name')->textarea(['rows' => 6])->label('Название сезона') ?>
    <?php endif;?>
    <?php if($_GET['create']=='quest'):?>
        <?= $form->field($model, 'label')->textarea(['rows' => 2])->label('Текст вопроса') ?>
        <?= $form->field($model, 'season')->hiddenInput(['value'=>$_GET['backid']])->label('') ?>
        <?= $form->field($model, 'invert')->radioList(['0' => 'Не инвертированный', '1' => 'Инвертированный',],['value'=>0])->label('Тип вопроса') ?>
        <?= $form->field($model, 'descr')->radioList(['0' => 'Социальную', '1' => 'Производственную','3' => 'Неопределённую'])->label('Вопрос описывает оценку') ?>
        <?= $form->field($model, 'type')->radioList(['0' => 'Числа', '1' => 'Текста'])->label('Ответы на вопрос в виде') ?>
    <?php endif;?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
