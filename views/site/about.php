<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
$who = (new \yii\db\Query())
->select(['otvet'])
->from('answers')
->where(['id_about' => Yii::$app->user->getId()])
->all();
$questions = (new \yii\db\Query())
->select(['label'])
->from('questions')
->all();
$tempoid=(new \yii\db\Query())
->select(['email'])
->from('user')
->where(['id'=>Yii::$app->user->getId()])
->all();
$tempo=(new \yii\db\Query())
->select(['id','fi','photo'])
->from('svazusers')
->where(['email'=>$tempoid['0']['email']])
->all();
$questioncount=(new \yii\db\Query())
->select(['id'])
->from('questions')
->count();
if($tempo){
   $answers=(new \yii\db\Query())
   ->select(['id','otvet','id_question'])
   ->from('answers')
   ->where(['id_about'=>$tempo[0]['id']])
   ->all();
}
$countforanswers=1;
$colors=array('yellow','blue','red','purple','cyan','green','pink','black','orange','gray','violet','lightgreen','lime','lightblue','magenta','darkcyan','darkgray','aquamarine','aqua','darkkhaki','coral','chocolate');
$answersresult=0;
$width=0;
if($tempo){
   echo'<div class="site-about">
   <div class="graphic-block-about">
   <div class="graphic">
   <h3 class="question">Ваша статистика:</h3>
   <p style="margin-left:-8%;margin-top:92px; margin-bottom:-400px;font-size:30px;"><b>н<br>и<br>к<br>о<br>г<br>д<br>а</b></p>
   <p style="margin-bottom:-370px;margin-top:92px;margin-left:105%; font-size:30px;"><b>в<br>с<br>е<br>г<br>д<br>а</b></p>
   <div class="formax">';
   for ($i=0; $i < $questioncount; $i++) {
      $width=0;
      $answersresult=0;
      $answerscount=(new \yii\db\Query())
      ->select(['id'])
      ->from('answers')
      ->where(['id_about'=>$tempo[0]['id'],'id_question'=>$countforanswers])
      ->count();
      $answerssummary=(new \yii\db\Query())
      ->select(['otvet'])
      ->from('answers')
      ->where(['id_about'=>$tempo[0]['id'],'id_question'=>$countforanswers])
      ->all();
      foreach ($answerssummary as $key => $value) {
         $answersresult+=$value['otvet'];
      }
      $answersresult=$answersresult/$answerscount;
      $width=$answersresult*100/5;
      echo '<div class="progress-line1" style="width: '.$width.'%;background-color:'.$colors[$i].';">';
                  $questions[$i]['label'] = str_replace('Иван Иванов','<b>'.$tempo[0]['fi'].'</b>', $questions[$i]['label']);
                    $questions[$i]['label'] = str_replace('Ивана Иванова','сотрудник <b>'.$tempo[0]['fi'].'</b>', $questions[$i]['label']);
                    echo'
                  <p class="score">'.round($answersresult).'</p>
                  </div>';
                  echo '<span class="hiddenprogress">'.$questions[$i]['label'].'</span>';
      $countforanswers+=1;
   }
}else{echo '<h1>К сожалению вас пока никто не оценил :(</h1>';}
?>
</div>
</div>
</div>
