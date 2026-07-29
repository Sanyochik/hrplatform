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
        $lasttriger = (new \yii\db\Query())
            ->select(['triger'])
            ->from('answers')
            ->max('triger');
        $lasttriger+=2;
            $tempo=array();
            $arrayfortest=array(219,228,55,105,33,88,54,2,136,113);
            $arrayusers=array();
                foreach ($arrayfortest as $key => $value) {
                    $currentuser = (new \yii\db\Query())
                    ->select(['triger','id_about'])
                    ->from('answers')
                    ->where(['id_about'=>$value,'id_creator'=>Yii::$app->user->getId()])
                    ->all();
                    if(empty($currentuser)){
                        array_push($arrayusers, $value);
                    }
                }
                if(!empty($arrayusers)){
                    $tempo=(new \yii\db\Query())
                    ->select(['id','fi','photo'])
                    ->from('svaz')
                    ->where(['id_otv'=>Yii::$app->user->getId(),'type'=>[2],'id_svaz'=>$arrayusers])
                    ->join('INNER JOIN', 'svazusers','id_svaz = svazusers.id')
                    ->all();
                }
        if(empty($tempo)){
        for ($i=1; $i < $lasttriger; $i++) { 
            $currentusers = (new \yii\db\Query())
            ->select(['id_about'])
            ->from('answers')
            ->where(['id_creator'=>Yii::$app->user->getId()])
            ->all();
            $left=array();
            foreach ($currentusers as $key => $value) {
                array_push($left, $value['id_about']);
            }
            $currentuser = (new \yii\db\Query())
            ->select(['id_about'])
            ->from('answers')
            ->where(['triger'=>$i])
            ->all();
            foreach ($currentuser as $key => $value) {
                array_push($left, $value['id_about']);
            }
            $tempo=(new \yii\db\Query())
            ->select(['id','fi','photo'])
            ->from('svaz')
            ->where(['id_otv'=>Yii::$app->user->getId(),'type'=>[2]])
            ->join('INNER JOIN', 'svazusers','id_svaz = svazusers.id')
            ->andwhere(['NOT IN','id', $left])
            ->all();
            if($tempo){
                $i=$lasttriger;
            }else{
            }
        }
        }
        if(($tempo)&&($_SESSION['count']<10)){
            $maxtriger = (new \yii\db\Query())
            ->select(['triger'])
            ->from('answers')
            ->where(['id_about'=>$tempo[0]['id']])
            ->max('triger');
            if(!$maxtriger){
                $maxtriger = 0;
            }
            echo '<img src="/web/img/'.$tempo[0]['photo'].'"style="width:410px;height: 410px;">';
            echo '<input type="text" name="to" value="'.$tempo[0]['id'].'" style="display: none;">';
            echo '<label class="label-class">Оцениваете сотрудника: '.$tempo[0]['fi'].'</label><br>';
            $array = (new \yii\db\Query())
            ->from('questions')
            ->all();
            echo'<br>';
            echo $form->field($model, 'triger')->textInput(['autofocus' => true,'style'=>'display:none;','value'=>$maxtriger])->label('',['class'=>'label-class']);
            foreach ($array as $key => $value) {
                if($value['id'] == 13){
                    $value['label']=str_replace('Иван Иванов', '<b>'.$tempo[0]['fi'].'</b>', $value['label']);
                    $value['label']=str_replace('Ивана Иванова', ' сотрудника <b>'.$tempo[0]['fi'].'</b>', $value['label']);
                    echo $form->field($model, 'id_question')->radioList(array(1 => '1', 2 => '2', 3 => '3', 4 => '4'), array('name'=>$value['id']))->label($value['label']);
                    echo '<div style="margin-top:-15px;"><label class="form-check-label"><input value="5" class="form-check-input" type="radio" name="'.$value["id"].'" required>   5</label></div>';
                }else{
                    $value['label']=str_replace('Иван Иванов', '<b>'.$tempo[0]['fi'].'</b>', $value['label']);
                    $value['label']=str_replace('Ивана Иванова', ' сотрудника <b>'.$tempo[0]['fi'].'</b>', $value['label']);
                    echo $form->field($model, 'id_question')->radioList(array(1 => 'Почти никогда', 2 => 'Редко', 3 => 'В половине случаев', 4 => 'Часто'), array('name'=>$value['id']))->label($value['label']);
                    echo '<div style="margin-top:-15px;"><label class="form-check-label"><input value="5" class="form-check-input" type="radio" name="'.$value["id"].'" required>   Почти всегда</label></div>';
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
