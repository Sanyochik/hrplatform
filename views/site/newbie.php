<?php

/** @var yii\web\View $this */

$this->title = 'Новенькие';
?>
<div class="site-index">

    <div class="d-flex flex-row justify-content-center align-items-center" style="margin-left:auto;margin-right:auto;width:1000px;" >
        
        <div class="row" style="margin-bottom:40px;min-height:380px;">
           
           <?php
        $arraynewbies = (new \yii\db\Query())
        ->select(['id','fi','email','photo','departamentid','incomp','birthday'])
        ->from('svazusers')
        ->where(['>','incomp', date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-14, date("Y")))])
        ->andwhere(['<','incomp', date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+14, date("Y")))])
        ->all();
        $arraydeparts = (new \yii\db\Query())
        ->select(['id','name'])
        ->from('departament')
        ->all();
            foreach ($arraynewbies as $key => $value) {
                echo'
                <div class="col-lg-4" style="min-width:300px;border-radius: 20px;min-height:350px;box-shadow: 1px 1px 5px gray;position: relative;margin-bottom:10px;margin-right:40px;">
                <h2 style="text-align: center;">'.$value['fi'].'</h2>

                <p class="d-flex flex-row justify-content-center align-items-center">
                    <img src="https://cross-map.sletat.ru/web/img/'.$value['photo'].'" width="130" height="130">
                </p>

                <p style="text-align: center;">
                    Из отдела:'.$arraydeparts[$value['departamentid']-1]['name'].'
                </p>

                <p style="text-align: center;">
                    Почта для связи: '.$value['email'].'
                </p>

                <p style="text-align: center;">
                    Присоединился к нам: '.date('d.m.Y',strtotime($value['incomp'])).'
                </p>

                <p style="text-align: center;">
                    День рождения: '.date('d.m',strtotime($value['birthday'])).'
                </p>

                </div>';
            }
        ?>

        </div>
</div>
