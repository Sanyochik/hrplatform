<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use app\models\Diagrams;

$this->title = 'Диаграмма';
$diagrams=new Diagrams();
if(!$_GET){
   $all = (new \yii\db\Query())
   ->select(['*'])
   ->from('svazusers')
   ->all();
   echo '<form method="GET" action="diagramm"><input name="search" type="text"><input type="submit" value="Поиск"></form>';
   $diagrams->generate($all);
}elseif(isset($_GET['search'])){
   $all = (new \yii\db\Query())
   ->select(['*'])
   ->from('svazusers')
   ->where(['like','fi',$_GET['search']])
   ->all();
   echo '<form method="GET" action="diagramm"><input name="search" value="'.$_GET['search'].'" type="text"><input type="submit" value="Поиск"></form>';
   $diagrams->generate($all);
}elseif(isset($_GET['soc'])){
   echo '<form method="GET" action="diagramm"><input name="search" type="text"><input type="submit" value="Поиск"></form>';
   if($_GET['soc']=='up')
   {
      $all = explode(',',$_GET['array']);
      $diagrams->generateorder($all);
   }
   elseif($_GET['soc']=='down'){
   $all = explode(',',$_GET['array']);
   $diagrams->generateorder($all);
   }
}elseif(isset($_GET['proiz'])){
   echo '<form method="GET" action="diagramm"><input name="search" type="text"><input type="submit" value="Поиск"></form>';
   if($_GET['proiz']=='up'){
      $all = explode(',',$_GET['array']);
      $diagrams->generateorder($all);
   }elseif($_GET['proiz']=='down'){
      $all = explode(',',$_GET['array']);
      $diagrams->generateorder($all);
   }
}