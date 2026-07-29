<?php

/** @var yii\web\View $this */

$this->title = 'Добро пожаловать';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 style="font-size:45px;" class="display-4">Привет, увидев фото сотрудника, тебе необходимо выбрать один из вариантов вашего взаимодействия.</h1>

        <p class="lead">Время на прохождение карты займет примерно 15 минут.</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Не знаю</h2>

                <p>Не знаю — с человеком не знаком, видел/не видел в офисе, контакта не было</p><br>

                <p><a class="btn btn-danger">Не знаю</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Знаю</h2>

                <p>Знаю — с человеком знаком, общались на кухне, обсуждали кино/коллег/погоду, но по важным рабочим задачам не взаимодействовали.</p>

                <p><a class="btn btn-warning">Знаю</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Работаем вместе</h2>

                <p>Работаем вместе — с человеком пересекаемся в рабочих задачах. Это человек может быть из твоего отдела, так и из другого бизнес-юнита.</p>

                <p><a class="btn btn-success">Работаем вместе</a></p>
            </div>
        </div>
        <div class="jumbotron text-center bg-transparent">

            <p><a class="btn btn-lg btn-success" href="/web/site/select">Создать свою карту связи</a></p>

        </div>

    </div>
</div>
