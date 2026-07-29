<?php
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\ajax;
use yii\models\Rest;
/** @var yii\web\View $this */
$i=1;
$canday = 9;
$rest = (new \yii\db\Query())
            ->select(['*'])
            ->from('rest')
            ->where(['user_id'=>Yii::$app->user->identity->id])
            ->all();
$this->title = 'Личный кабинет '.Yii::$app->user->identity->username.'';
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<div style="width:60%;" class="container">
	<h4>Добро пожаловать в личный кибинет, <?=Yii::$app->user->identity->username?></h4>
	<h5>Должность: Инженер технической поддержки</h5>
	<p>Дата устройства на работу: 01.08.2021<br>
	На данный момент у вас накопилось: 9 дней отпуска</p>
		<div style="width:50%;" class="container">
		<button id="calendar" style="margin-left:auto;margin-right:auto;display:block;" class='btn-success btn'>Потратить дни</button>
		<form id="form" method="GET" action="newrest" style="margin-top:20px;display:none;">
			<input class="form-control" name="date" type="date" min="<?=date("Y-m-d");?>">
			<input type="hidden" value="<?=Yii::$app->user->identity->id?>" name="creator">
			<select style="margin-top:20px;" name="days" class="form-control">
			<?php while ($i<$canday){
				echo '<option>'.$i.'</option>';
				$i+=1;
			}
			$i=1;
			?>
			</select>
			<div style="width:100%;">
				<input style="margin-top:20px;margin-left:auto;margin-right:auto;display:block;" class="btn btn-success" type="submit" value="Подать заявку">
			</div>
		</form>
	</div>
</div>
<div style="display:flex;width:100%;flex-wrap: wrap;height: auto;">
	<?php
	foreach ($rest as $key => $value) {
		echo'<div style="height:160px;width:160px;margin-left:50px; margin-top:20px;" class="btn-';
		if($value['status']=='Aprove'){
			echo'success';
		}elseif($value['status']=='Denied'){
			echo'danger';
		}else{
			echo'secondary';
		}
		echo ' btn"><h5>'.date('d.m.Y',strtotime($value['create_at'])).'</h5><p>Статус: ';
		if($value['status']=='Aprove'){
			echo'одобрено';
		}elseif($value['status']=='Denied'){
			echo'отказано';
		}else{
			echo'создано';
		}
		echo'</p><p>C '.date('d.m.Y',strtotime($value['rest_at'])).'</p><p> на '.$value['days'].' дней</p></div>';
	}
	?>
</div>
<script>
	$('#calendar').on('click', function () { 
		var e = document.getElementById('form');
        e.style.display = (e.style.display == 'block') ? 'none' : 'block';
        var e = document.getElementById('calendar');
        e.style.display = (e.style.display == 'none') ? 'block' : 'none';
    });
</script>