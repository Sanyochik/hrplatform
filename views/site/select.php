<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
 
$this->title = 'Создание карты связи';
$this->params['breadcrumbs'][] = $this->title;
    $arraysvazusers = (new \yii\db\Query())
    ->select(['id','fi','email','photo','departamentid'])
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
<?php $form = ActiveForm::begin(['id' => 'form-signup','action' => 'newsvaz']); ?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!$_GET){
        echo '<h3>Привет, увидев фото сотрудника, тебе необходимо выбрать один из вариантов вашего взаимодействия.</h3>';
    }?>
    <div class="row">
        <div class="col-lg-5">
        <div <?="style='background:url(/web/img/".$tempo[0]['photo'].");background-size: contain;background-repeat:no-repeat; background-position: center center;width: 100%;height:400px; margin-left:-10%;'"?>></div>
            <div class="form-group">
                <input type="text" name="from" <?='value="'.Yii::$app->user->id.'"'?> style="display: none;">
                <input type="text" name="to" <?='value="'.$tempo[0]['id'].'"'?> style="display: none;">
                <label class="label-class" style="font-size:20px; width:900px;">Знаете ли вы сотрудника: <b style="font-size:25px;"><?=$tempo[0]['fi']?></b></label><br>
            </div>
            <?php if ((isset($_GET['lastid']))&&(!isset($_GET['actualsvaz']))){
            echo'<a href="delsvaz?lastid='.$_GET['lastid'].'"><div class="btn btn-warning">Вернуться назад
            </div></a>';
            }?>
            <div style="margin-left: 95%; width:153px">
                <div style="margin-top: -435px;"  class="hover1">
                    <?= Html::submitButton('Не знаю', ['class' => 'btn btn-danger', 'name' => 'submit-button','value'=>'0'])?>
                </div>
                    <span class="hidden1">Не знаком с этим сотрудником</span>
                <div style="margin-top: 20px;"  class="hover2">
                    <?= Html::submitButton('Знаю', ['class' => 'btn btn-warning', 'name' => 'submit-button','value'=>'1']) ?>
                </div>
                    <span class="hidden2">Знаю этого сотрудника, но не взаимодействую с ним по работе</span>
                <div style="margin-top: 20px;" class="hover3">
                    <?= Html::submitButton('Работаем вместе', ['class' => 'btn btn-success', 'name' => 'submit-button','value'=>'2']) ?>
                </div>
                    <span class="hidden3">Взаимодействую с этим сотрудником по работе</span>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php 
else:
?>
<script>

<?php 

    $links = (new \yii\db\Query())
    ->select(['su.id as originalId', 'sua.id as targetId', 's.type'])
    ->from('svaz as s')
    ->join('LEFT JOIN', 'user as u','s.id_otv = u.id')
    ->join('LEFT JOIN', 'svazusers as su','u.email = su.email')
    ->join('LEFT JOIN', 'svazusers as sua','sua.id = s.id_svaz')
    ->where(['type'=> [2]])
    ->andWhere(['IS NOT', 'su.id', null])
    ->all();
    
    $popularity = (new \yii\db\Query())
    ->select(['id_svaz as userId', 'count(1) as cnt'])
    ->from('svaz')
    ->where(['type'=> [1,2]])
    ->groupBy(['id_svaz'])
    ->all();
    function findPopularCountById($userId, $popularity){
          foreach($popularity as $popular) {
              if ($popular['userId'] == $userId) {
                  return $popular['cnt'];
              }
          }
          return 0;
      }
?>
const nodes = [<?php  
$x = -1000;
$y = -500;

$departaments = array();

foreach($arraysvazusers as $user) {
      $group = $user['departamentid'] == null ? 25 : $user['departamentid'];
      $departaments[$group] = $departaments[$group] + 1;
      $xc = $x + $group * 250 + $departaments[$group] * 40;
      $yc = $y + ($group % 5) * 400 + $departaments[$group] % 3 * 100;
      $pc = findPopularCountById($user['id'], $popularity);
      echo '{id: '.$user['id'].', label: "'.$user['fi'].'\r\n Меня знает: '.$pc.' коллег", shape: "circularImage", image: "/web/img/'.$user['photo'].'", group: '.$group.', x: '.$xc.', y: '.$yc.'},';
} ?>];

const edges = [<?php 

function findLinkById($targetId, $type){
    foreach($links as $l ) {
        if ($l['originalId'] == $targetId) {
            return $l['type'] == $type;
        }
    }
    return false;
}
foreach($links as $link) { 
      //$t = $link['type'] == "2" ? 'green' : 'blue';
      $typeArrow = findLinkById($link['targetId'], $link['type']) ? 'to, from' : 'to';
      //echo '{from: '.$link['originalId'].', to: '.$link['targetId'].', arrows: "to", color: { color: "'.$t.'"}},';
      echo '{from: '.$link['originalId'].', to: '.$link['targetId'].', arrows: "'.$typeArrow.'"},';
}
      ?>];

</script>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'form-signup','action' => 'newsvaz']); ?>
                <div class="form-group">
                    <h1>Поздравляю вы составили вашу карту связи</h1>
                    <?php if ((isset($_GET['lastid']))&&(!isset($_GET['actualsvaz']))){
                        echo'<a href="delsvaz?lastid='.$_GET['lastid'].'"><div class="btn btn-warning">Вернуться назад
                    </div></a>';
                    }?>
                </div>
                <div id="mynetwork" style="width: 1920px;height: 1600px;border: 1px solid lightgray;"></div>
                <script src="https://visjs.github.io/vis-network/standalone/umd/vis-network.min.js"></script>
             <script src="/web/js/draw_charts.js"></script>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>
<?php
endif;
?>