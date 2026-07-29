<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Диаграмма';
$all = (new \yii\db\Query())
->select(['*'])
->from('svazusers')
->all();
$counter=0;
$margin=0;
$socialarray=array(1,6);
$socialinvertive = array(4,12);
$unsocialarray=array(2,3,5,7,9,10);
$socialsorter=array();
$unsocialsorter=array();
echo '
<table id="q-graph">
<caption>Общая статистика</caption>
<tbody>';
foreach ($all as $key => $value){
   $social = (new \yii\db\Query())
   ->select(['otvet','id_question'])
   ->from('answers')
   ->where(['id_about'=>$value['id'],'skip'=>null])
   ->andwhere(['IN','id_question',$socialarray])
   ->all();
   $socialinvert = (new \yii\db\Query())
   ->select(['otvet','id_question'])
   ->from('answers')
   ->where(['id_about'=>$value['id'],'skip'=>null])
   ->andwhere(['IN','id_question',$socialinvertive])
   ->all();
   $count = 0;
   foreach($socialinvert as $keys => $values){
      if($values['otvet']==1){
         $socialinvert[$count]['otvet']=5;
      }
      if($values['otvet']==2){
         $socialinvert[$count]['otvet']=4;
      }
      if($values['otvet']==3){
         $socialinvert[$count]['otvet']=3;
      }
      if($values['otvet']==4){
         $socialinvert[$count]['otvet']=2;
      }
      if($values['otvet']==5){
         $socialinvert[$count]['otvet']=1;
      }
      $count+=1;
   }
   $socialtotal = count($socialinvert) + count($social);
   $unsocial = (new \yii\db\Query())
   ->select(['otvet','id_question'])
   ->from('answers')
   ->where(['id_about'=>$value['id'],'skip'=>null])
   ->andwhere(['IN','id_question',$unsocialarray])
   ->all();
   // echo count($unsocial);
   // echo $value['fi'];
   $socialresult = array_sum(array_column($social, 'otvet'));
   $socialinvertresult = array_sum(array_column($socialinvert, 'otvet'));
   $unsocialresult = array_sum(array_column($unsocial, 'otvet'));
   $socialtotalscore = $socialinvertresult + $socialresult;
   // echo $socialtotalscore;
   // echo '<br>';
   // echo $unsocialresult;
   // echo 'stop';
   // echo '<br>';
   if($socialtotal != 0){
      $socialtotalscore = $socialtotalscore/$socialtotal;
      $unsocialresult = $unsocialresult/count($unsocial);
   }else{
      $socialtotalscore = 0;
      $unsocialresult = 0;
   }
   array_push($socialsorter,$socialtotalscore);
   array_push($unsocialsorter,$unsocialresult);
   $socialpercent=round($socialtotalscore,1)*20;
   $unsocialpercent=round($unsocialresult,1)*20;
   echo'
   <tr style="margin-left:'.$margin.'px;" class="qtr" id="q'.$counter.'">
   <th scope="row"><a href="/web/svaz/view?id='.$value['id'].'&know=7">'.$value['fi'].'</a></th>
   <td class="sent bar" style="height: '.$socialpercent.'%"><p style="margin-top:10px;">'.round($socialtotalscore,1).'</p></td>
   <td class="paid bar" style="height: '.$unsocialpercent.'%"><p style="margin-top:10px;">'.round($unsocialresult,1).'</p></td>
   </tr>';
   $counter+=1;
   $margin+=150;
}
echo'
</tr>
</tbody>
</table>
<div id="ticks">
<div class="tick" style="height: 59px;"><p>5</p></div>
<div class="tick" style="height: 59px;"><p>4</p></div>
<div class="tick" style="height: 59px;"><p>3</p></div>
<div class="tick" style="height: 59px;"><p>2</p></div>
<div class="tick" style="height: 59px;"><p>1</p></div>
</div>
<div style="align-items:center;text-align:center;margin-left:650px;margin-top:-500px; width:150px;">
<p>Социальная</p>
<div class ="sent"></div>
<br>
<p>Производcтвенная</p>
<div class ="paid"></div>
</div>
';