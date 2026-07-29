<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Вопросник';
?>
<div class="site-contact">
   <div class="row">
    <div class="col-lg-5">
        <h3>Вы оцениваете сотрудника:</h3>
        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
        <?php
        // $last=1;
        // for ($i=1; $i < 1000; $i++) { 
        //     $triger = (new \yii\db\Query())
        //     ->select(['id_about'])
        //     ->from('answers')
        //     ->where(['id_creator'=>Yii::$app->user->getId()])
        //     ->andwhere(['triger'=>$i])
        //     ->all();
        //     if($triger){
        //         foreach ($triger as $key => $value) {
        //             array_push($left, $value['id_about']);
        //         }
        //     }elseif($last!=2){
        //         $last+=1;
        //     }elseif ($last==2) {
        //        $i=1000;
        //     }
        // }
        $currentuser = (new \yii\db\Query())
        ->select(['id_about'])
        ->from('answers')
        ->where(['id_creator'=>Yii::$app->user->getId()])
        ->all();
        $left=array();
        foreach ($currentuser as $key => $value) {
            array_push($left, $value['id_about']);
        }
        $tempo=(new \yii\db\Query())
        ->select(['id','fi','photo'])
        ->from('svaz')
        ->where(['id_otv'=>Yii::$app->user->getId(),'type'=>[1,2]])
        ->join('INNER JOIN', 'svazusers','id_svaz = svazusers.id')
        ->andwhere(['NOT IN','id', $left])
        ->all();
        if($tempo){
            echo '<img src="/web/img/'.$tempo[0]['photo'].'"style="width:410px;height: 410px;">';
            echo '<input type="text" name="to" value="'.$tempo[0]['id'].'" style="display: none;">';
            echo '<label class="label-class">Оцениваете сотрудника: '.$tempo[0]['fi'].'</label><br>';
            $array = (new \yii\db\Query())
            ->from('questions')
            ->all();
            echo'<br>';
            foreach ($array as $key => $value) {
                if($value['id'] == 13){
                    $value['label']=str_replace('Иван Иванов', $tempo[0]['fi'], $value['label']);
                    $model->id_question = '1';
                    echo $form->field($model, 'id_question')->radioList(array(1 => '1', 2 => '2', 3 => '3', 4 => '4',5 => '5'), array('name'=>$value['id']))->label($value['label']);
                }else{
                    $value['label']=str_replace('Иван Иванов', $tempo[0]['fi'], $value['label']);
                    $model->id_question = '1';
                    echo $form->field($model, 'id_question')->radioList(array(1 => 'Почти никогда', 2 => 'Редко', 3 => 'В половине случаев', 4 => 'Часто',5 => 'Почти всегда'), array('name'=>$value['id']))->label($value['label']);
                }
            }
        echo '<div class="form-group">';
        echo Html::submitButton('Далее', ['class' => 'btn btn-primary']);
        echo '</div>';
        ActiveForm::end();
        }else{
            echo 'Вы оценили достаточно коллег на сегодня';
        }
        ?>



    </div>
</div>
</div>
