<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
 
$this->title = 'Создание карты связи';
$this->params['breadcrumbs'][] = $this->title;
    $arraysvazusers = (new \yii\db\Query())
    ->select(['id','fi','email','photo'])
    ->from('svazusers')
    ->all();
    $tempo=(new \yii\db\Query())
    ->select(['id','fi','email','photo'])
    ->from('svazusers')
    ->join('LEFT JOIN', 'svaz','id = svaz.id_svaz')
    ->where([
        'svaz.id_otv' => Yii::$app->user->id,
    ])
    ->all();
    $curentemail = (new \yii\db\Query())
    ->select(['email'])
    ->from('user')
    ->where(['id'=>Yii::$app->user->id])
    ->one();
    $currentuser = (new \yii\db\Query())
    ->select(['id'])
    ->from('svazusers')
    ->where(['email'=>$curentemail['email']])
    ->one();
    if($currentuser){
        $left=array($currentuser['id']);
    }else{
        $left=array();
    }
    $first=1;
    foreach ($tempo as $key => $value) {
        array_push($left, $value['id']);
    }
    $tempo=(new \yii\db\Query())
    ->select(['id','fi','email','photo'])
    ->from('svazusers')
    ->where(['NOT IN','id', $left])
    ->all();
    if($tempo):
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-signup','action' => 'newsvaz']); ?>
                <img <?="src='/web/img/".$tempo[0]['photo']."'"?> style="width:410px;height: 410px;">
                <div class="form-group">
                    <input type="text" name="from" <?='value="'.Yii::$app->user->id.'"'?> style="display: none;">
                    <input type="text" name="to" <?='value="'.$tempo[0]['id'].'"'?> style="display: none;">
                    <label class="label-class">Взаимодействуете ли вы с <?=$tempo[0]['fi']?></label><br>
                    <?= Html::submitButton('Не работаю', ['class' => 'btn btn-danger', 'name' => 'submit-button','value'=>'0'])?>
                    <?= Html::submitButton('Общаюсь не по работе', ['class' => 'btn btn-warning', 'name' => 'submit-button','value'=>'1']) ?>
                    <?= Html::submitButton('Работаю', ['class' => 'btn btn-success', 'name' => 'submit-button','value'=>'2']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>
<?php 
else:
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-signup','action' => 'newsvaz']); ?>
                <div class="form-group">
                    <h1>Поздравляю вы составили вашу карту связи</h1>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>
<?php
endif;
?>