<?php
 
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
 
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
    $arrayforgroups = (new \yii\db\Query())
    ->select(['groups.id','name','id_otv','username'])
    ->from('groups')
    ->rightJoin('user','id_otv=user.id')
    ->all();
    $groups=array();
    foreach ($arrayforgroups as $key => $value) {
        if($value['id_otv']!=''){
            $groups+=[$value["id"]=>$value["username"]];
        }
    }
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Для регистрации на платформе заполните поля ниже</p>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'email')->label('Введите ваш email',['class'=>'label-class']) ?>
                <?php if($_GET){
                    echo '<h5 style="color:red">Для регистрации пожалуйста используйте рабочую почту</h2>';
                }?>
                <div class="form-group">
                    <a href="/foget">Потеряли доступ к платформе?</a><br><br>
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>