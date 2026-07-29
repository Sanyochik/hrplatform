<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\svazusers */

$this->title = $model->fi;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи для карты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="svazusers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fi:ntext',
            'email:ntext',
            'photo:ntext',
        ],
    ]) ?>
    <?php
    $currentuser = (new \yii\db\Query())
    ->select(['id'])
    ->from('user')
    ->where(['email'=>$model->email])
    ->one();
    if($currentuser){
        $knowcount = (new \yii\db\Query())
        ->select(['id_svazi'])
        ->from('svaz')
        ->where(['id_svaz' => $_GET["id"],'type'=>'1'])
        ->count();
        $knowwho = (new \yii\db\Query())
        ->select(['id_svazi','fi','photo'])
        ->from('svaz')
        ->where(['id_svaz' => $_GET["id"],'type'=>'1'])
        ->join('INNER JOIN', 'user','svaz.id_otv = user.id')
        ->join('INNER JOIN', 'svazusers','user.email = svazusers.email')
        ->all();
        $workcount = (new \yii\db\Query())
        ->select(['id_svazi'])
        ->from('svaz')
        ->where(['id_svaz' => $_GET["id"],'type'=>'2'])
        ->count();
        $workwho = (new \yii\db\Query())
        ->select(['id_svazi','fi','photo'])
        ->from('svaz')
        ->where(['id_svaz' => $_GET["id"],'type'=>'2'])
        ->join('INNER JOIN', 'user','svaz.id_otv = user.id')
        ->join('INNER JOIN', 'svazusers','user.email = svazusers.email')
        ->all();
        $workwithcount = (new \yii\db\Query())
        ->select(['id_svazi'])
        ->from('svaz')
        ->where(['id_otv' => $currentuser['id'],'type'=>'2'])
        ->count();
        $workwithwho = (new \yii\db\Query())
        ->select(['id_svazi','fi','photo'])
        ->from('svaz')
        ->where(['id_otv' => $currentuser['id'],'type'=>'2'])
        ->join('INNER JOIN', 'svazusers','svaz.id_svaz = svazusers.id')
        ->all();
        $knowwithcount = (new \yii\db\Query())
        ->select(['id_svazi'])
        ->from('svaz')
        ->where(['id_otv' => $currentuser['id'],'type'=>'1'])
        ->count();
        $knowwithwho = (new \yii\db\Query())
        ->select(['id_svazi','fi','photo'])
        ->from('svaz')
        ->where(['id_otv' => $currentuser['id'],'type'=>'1'])
        ->join('INNER JOIN', 'svazusers','svaz.id_svaz = svazusers.id')
        ->all();
        $couplecount = (new \yii\db\Query())
        ->select(['id_svazi','id_svaz'])
        ->from('svaz')
        ->where(['id_otv' => $currentuser['id'],'type'=>'2'])
        ->join('INNER JOIN', 'svazusers','svaz.id_svaz = svazusers.id')
        ->all();
        $couplecountresult=array();
        $uncouplecountresult=array();
        foreach ($couplecount as $key => $value) {
            $aboutnow = (new \yii\db\Query())
            ->select(['user.id','fi','photo'])
            ->from('svazusers')
            ->where(['svazusers.id' => $value['id_svaz']])
            ->join('INNER JOIN', 'user','svazusers.email = user.email')
            ->all();
            $couplecount2 = (new \yii\db\Query())
            ->select(['id_svazi'])
            ->from('svaz')
            ->where(['id_otv' => $aboutnow[0]['id'],'type'=>'2','id_svaz'=>$_GET['id']])
            ->join('INNER JOIN', 'svazusers','id_svaz = svazusers.id')
            ->all();
            if($couplecount2){
                array_push($couplecountresult, $aboutnow);
            }else{
                array_push($uncouplecountresult, $aboutnow);
            }
        }
        $whoanswer = (new \yii\db\Query())
        ->select(['user.id','fi','photo'])
        ->from('answers')
        ->where(['id_about' => $_GET['id']])
        ->join('INNER JOIN', 'user','answers.id_creator = user.id')
        ->join('INNER JOIN', 'svazusers','user.email = svazusers.email')
        ->GROUPBY(['id_creator'])
        ->all();
        echo 'Данного пользователя знают: '.$knowcount.' пользователей <a href="view?id='.$_GET['id'].'&know=1" class="btn btn-primary">Подробнее</a><br>';
        echo 'С данным пользователем работают '.$workcount.' пользователем <a href="view?id='.$_GET['id'].'&know=2" class="btn btn-primary">Подробнее</a><br>';
        echo 'Этот пользователь работает с: '.$workwithcount.' пользователями <a href="view?id='.$_GET['id'].'&know=3" class="btn btn-primary">Подробнее</a><br>';
        echo 'Этот пользователь знает: '.$knowwithcount.' пользователей <a href="view?id='.$_GET['id'].'&know=4" class="btn btn-primary">Подробнее</a><br>';
        echo 'Этот пользователь сделал: '.count($couplecountresult).' взаимных связей <a href="view?id='.$_GET['id'].'&know=5" class="btn btn-primary">Подробнее</a><br>';
        echo 'Этот пользователь не получил взаимные связи с : '.count($uncouplecountresult).' пользователем <a href="view?id='.$_GET['id'].'&know=6" class="btn btn-primary">Подробнее</a><br>';
        echo 'Статистика по вопросам<a href="view?id='.$_GET['id'].'&know=7" class="btn btn-primary">Подробнее</a><br>';
        echo 'Кто оценил пользователя: '.count($whoanswer).' <a href="view?id='.$_GET['id'].'&know=8" class="btn btn-primary">Подробнее</a>';
        if(isset($_GET['know'])){
            if($_GET['know'] == 1){
                foreach ($knowwho as $key => $value) {
                    echo '<br>';
                    echo "<div style=' background:url(/web/img/".$value['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value['fi']."</div></div>";
                }
            }elseif($_GET['know'] == 2){
                foreach ($workwho as $key => $value) {
                    echo '<br>';
                    echo "<div style='background:url(/web/img/".$value['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value['fi']."</div></div>";
                }
            }elseif($_GET['know'] == 3){
                foreach ($workwithwho as $key => $value) {
                    echo '<br>';
                    echo "<div style='background:url(/web/img/".$value['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value['fi']."</div></div>";
                }
            }elseif($_GET['know'] == 5){
                foreach ($couplecountresult as $key => $value) {
                    echo '<br>';
                    echo "<div style='background:url(/web/img/".$value[0]['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value[0]['fi']."</div></div>";
                }
            }elseif($_GET['know'] == 6){
                foreach ($uncouplecountresult as $key => $value) {
                    echo '<br>';
                    echo "<div style='background:url(/web/img/".$value[0]['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value[0]['fi']."</div></div>";
                }
            }elseif($_GET['know'] == 8){
                foreach ($whoanswer as $key => $value) {
                    echo '<br>';
                    echo "<div style='background:url(/web/img/".$value['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value['fi']."</div></div>";
                }
            }elseif($_GET['know']==7){
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
                ->where(['id'=>$_GET['id']])
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
              if($answers){
                echo '
              <div class="site-about">
              <div class="graphic-block">
              <h3 class="question">Ваша статистика:</h3>
              <div class="graphic">
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
                  echo '<div class="progress-line1" style="width: '.$width.'%;background-color:'.$colors[$i].';">
                  <p class="score">'.$answersresult.'</p>
                  </div>';
                  $countforanswers+=1;
              }
              echo'</div>
              </div>
              <h1>Легенда диаграммы:</h1>
              <div class="history">';
              if($tempo){
                  foreach ($questions as $key => $value) {
                    $value['label'] = str_replace('Иван Иванов','<b>'.$tempo[0]['fi'].'</b>', $value['label']);
                    $value['label'] = str_replace('Ивана Иванова','сотрудник <b>'.$tempo[0]['fi'].'</b>', $value['label']);
                    echo '<div class="colleg" style="background-color: '.$colors[$key].';">
                    </div>
                    <p style="margin-left:10px;margin-bottom:15px;">'.$value['label'].'</p>';
                }
            }
        }else{ echo '<h1>К сожалению пользователя: '.$tempo[0]['fi'].' пока никто не оценил :(</h1>';}
            echo'
            </div>
            </div>
            </div>';
        }else{
            foreach ($knowwithwho as $key => $value) {
                echo '<br>';
                echo "<div style='background:url(/web/img/".$value['photo'].");background-size: contain;background-repeat:no-repeat;width: 100%;height:50px;'><div style='margin-left:70px; font-size:30px;'>".$value['fi']."</div></div>";
            }
        }
    }
}else{
        echo 'Данный пользователь не начал проходить карту связи';
    }
    ?>
</div>
