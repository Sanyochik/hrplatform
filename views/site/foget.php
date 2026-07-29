<?php
 
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
 
$this->title = 'Восстановление';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Для восстановления учётной записи введите вашу почту</p>
    <p>После нажания на кнопку 'Восстановить' вам на почту придёт письмо с сылкой, на ваш аккаунт</p>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'email')->label('Введите ваш email',['class'=>'label-class']) ?>
                <?php if($_GET){
                    echo '<h5 style="color:red">Указанной почты нет в системе</h2>';
                }?>
                <div class="form-group">
                    <?= Html::submitButton('Восстановить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>