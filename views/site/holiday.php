<?php

/** @var yii\web\View $this */

$this->title = 'Главная';
?>
<style>
.show {
    display: none;
    background-color:cyan;
    position: absolute;
    color:blue;
    width:300px;
    height:100px;
    margin-left:-120px;
    z-index: 4;
    text-align: center;
}
    
.hover:hover .show {
    display: block;
}
</style>
<div class="site-index">

    <div class="d-flex flex-row justify-content-center align-items-center" style="margin-left:auto;margin-right:auto;" >
        <div class="row" style="margin-bottom:40px;">
            <?php
            $daysofyear = cal_days_in_month(CAL_GREGORIAN, 11, date('y'));
            for ($i=1; $i <= $daysofyear; $i++) {
                $birthday = (new \yii\db\Query())
                ->select(['id','fi','email','photo','departamentid','incomp','birthday'])
                ->from('svazusers')
                ->where(['=','MONTH(birthday)', 11])
                ->andwhere(['=','DAY(birthday)', $i])
                ->all();
                $holidays = (new \yii\db\Query())
                ->select(['id','name','description','date'])
                ->from('holidays')
                ->where(['=','date', date("Y-m-".$i."")])
                ->all();
                if(isset($holidays[0]['id'])){
                    echo'    
                        <div class="col-lg-4 hover" style="max-width:100px;min-height:100px;border-radius: 20px;box-shadow: 1px 1px 5px gray;margin-right:10px;margin-bottom:5px;position: relative; background-color:orange;">
                            <h2 style="text-align: center;margin-top:25px;">'.$i.'</h2>';
                            echo '<p class="show">';
                                foreach ($holidays as $key => $value) {
                                    echo $value['name'].'<br>';
                                }
                                if(isset($birthday[0]['id'])){
                                    foreach ($birthday as $key => $value) {
                                        echo'День рождения у '.$value['fi'].'<br>';
                                    }
                                }
                            echo '<p>';
                        echo'</div>';
                }elseif(isset($birthday[0]['id'])){
                    echo'    
                        <div class="col-lg-4 hover" style="max-width:100px;min-height:100px;border-radius: 20px;box-shadow: 1px 1px 5px gray;margin-right:10px;margin-bottom:5px;position: relative; background-color:green;">
                            <h2 style="text-align: center;margin-top:25px;">'.$i.'</h2>';
                            echo '<p class="show">';
                            foreach ($birthday as $key => $value) {
                                echo 'День рождения у '.$value['fi'].'<br>';
                            }
                            echo '</p>';
                        echo'</div>';
                }else{
                    echo'    
                        <div class="col-lg-4" style="max-width:100px;min-height:100px;border-radius: 20px;box-shadow: 1px 1px 5px gray;margin-right:10px;margin-bottom:5px;position: relative; background-color:gray;">
                            <h2 style="text-align: center;margin-top:25px;">'.$i.'</h2>

                        </div>';
                }
            }

            ?>
        </div>
    </div>
</div>
