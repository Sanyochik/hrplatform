<?php

/** @var yii\web\View $this */

$this->title = 'Главная';
?>
<div class="site-index">

    <div class="d-flex flex-row justify-content-center align-items-center" style="margin-left:auto;margin-right:auto;width:1000px;" >

        <div class="row" style="margin-bottom:40px;min-height:380px;">

            <div class="col-lg-4" style="max-width:300px;border-radius: 20px;max-height:430px;box-shadow: 1px 1px 5px gray;margin-right:40px;position: relative;">
                <h2 style="text-align: center;">Ценности</h2>

                <p style="text-align: center;">
                    Наши ценности
                </p>

                <p class="d-flex flex-row justify-content-center align-items-center"  style="position: absolute;bottom:0;left:0;right:0;">
                    <a href="/web/site/unique" class="btn btn-success">Подробнее</a>
                </p>

            </div>
            <?php
                $birthday = (new \yii\db\Query())
                ->select(['id','fi','email','photo','departamentid','incomp','birthday'])
                ->from('svazusers')
                ->where(['=','MONTH(birthday)', date("m")])
                ->andwhere(['=','DAY(birthday)', date("d")])
                ->all();
                $holidays = (new \yii\db\Query())
                ->select(['id','name','description','date'])
                ->from('holidays')
                ->where(['=','date', date("Y-m-d")])
                ->all();
                $months=array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
                if($holidays[0]['id']!=''){
                    echo'<div class="col-lg-4" style="max-width:300px;border-radius: 20px;max-height:430px;box-shadow: 1px 1px 5px gray;position: relative;">
                        <h2 style="text-align: center;">Сегодня отмечаем</h2>

                        <p style="text-align: center;">
                            Сегодня '.date("d ").''.$months[date("d")].' - '.$holidays[0]['name'].'
                        </p>

                        <p style="text-align: center; font-size:18px;">
                            '.$holidays[0]['description'].'
                        </p>

                        <p class="d-flex flex-row justify-content-center align-items-center"  style="position: absolute;bottom:0;left:0;right:0;">
                            <a href="/web/site/holiday" class="btn btn-success">Подробнее</a>
                        </p>

                    </div>';
                }
                elseif($birthday[0]['id']!=''){
                    echo'<div class="col-lg-4" style="max-width:300px;border-radius: 20px;max-height:430px;box-shadow: 1px 1px 5px gray;position: relative;">
                        <h2 style="text-align: center;">Сегодня отмечаем</h2>

                        <p class="d-flex flex-row justify-content-center align-items-center">
                            <img src="https://cross-map.sletat.ru/web/img/'.$birthday[0]['photo'].'" width="130" height="130">
                        </p>

                        <p style="text-align: center;">
                            Сегодня '.date("d ").''.$months[date("d")].' у сотрудника <b>'.$birthday[0]['fi'].'</b> день рождения.
                        </p>

                        <p style="text-align: center; font-size:18px;">
                            <b>Поздравляем его с этим замечательным днём!!!</b>
                        </p>

                        <p class="d-flex flex-row justify-content-center align-items-center"  style="position: absolute;bottom:0;left:0;right:0;">
                            <a href="/web/site/holiday" class="btn btn-success">Подробнее</a>
                        </p>

                    </div>';
                }else{
                    echo'<div class="col-lg-4" style="max-width:300px;border-radius: 20px;max-height:430px;box-shadow: 1px 1px 5px gray;position: relative;">
                        <h2 style="text-align: center;">Сегодня нет праздников(</h2>

                        <p style="text-align: center;">
                            Но вы всегда можете посмотреть ближайшие праздники
                        </p>

                        <p class="d-flex flex-row justify-content-center align-items-center"  style="position: absolute;bottom:0;left:0;right:0;">
                            <a href="/web/site/holiday" class="btn btn-success">Подробнее</a>
                        </p>

                    </div>';
                }
            ?>

            <div class="col-lg-4" style="min-height:450px;max-width:300px;border-radius: 20px; margin-left: 30px;text-align:center;box-shadow: 0px -5px 5px gray;position: relative;">

                <h2>Полезные ссылки</h2>

                <a href="https://agile.sletat.ru/confurl/pages/viewpage.action?pageId=180357578">Общие регламенты</a><br>
                <a href="https://agile.sletat.ru/confurl/pages/viewpage.action?pageId=83725448">Адаптация новичка</a><br>
                <a href="https://agile.sletat.ru/confurl/pages/viewpage.action?pageId=72255919">Кадровые вопросы</a>

            </div>

            <div class="col-lg-4" style="max-width:300px;max-height:430px;border-radius: 20px;box-shadow: 1px 1px 5px gray;margin-right:40px;position: relative;">

                <h2 style="text-align: center;">Friday News</h2>

                <p style="text-align: center;">
                Ваши любимые пятничные новости в одном месте
                </p>

                <p class="d-flex flex-row justify-content-center align-items-center"  style="position: absolute;bottom:0;left:0;right:0;">
                    <a class="btn btn-success">Подробнее</a>
                </p>

            </div>

            <div class="col-lg-4" style="max-width:300px;max-height:430px;border-radius: 20px;box-shadow: 1px 1px 5px gray;position: relative;">

                <h2 style="text-align: center;">Приветствуем новичков</h2>

                <p style="text-align: center;">
                    Тут вы можете узнать о новеньких на нашем борту
                </p>

                <p class="d-flex flex-row justify-content-center align-items-center"  style="position: absolute;bottom:0;left:0;right:0;">
                    <a  href="/web/site/newbie" class="btn btn-success">Подробнее</a>
                </p>

            </div>

            <div class="col-lg-4" style="min-height:450px;max-width:300px;border-radius: 20px; margin-left: 30px;box-shadow: 0px 5px 5px gray;margin-top:-50px;position: relative;">
            </div>

        </div>

    </div>
</div>
