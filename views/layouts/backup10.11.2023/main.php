<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'Sletat.ru',
        'brandUrl' => 'https://sletat.ru',
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    $countofsvaz = (new \yii\db\Query())
    ->select(['id_svazi'])
    ->from('svaz')
    ->count();
    $countofanswers = (new \yii\db\Query())
    ->select(['id_svazi'])
    ->from('answers')
    ->where(['is','skip',null])
    ->count();
    $userscount = (new \yii\db\Query())
    ->select(['id'])
    ->from('svazusers')
    ->count();
    $userscountall=$userscount*$userscount+2000;
    if($userscountall>$countofsvaz){
        $crosspercent=$countofsvaz*20/$userscountall;
    }else{
        $crosspercent=20;
    }
    $precentresult=$crosspercent;
    $userscountall=$userscount*130;
    if($userscountall>$countofanswers){
        $answerpercent=$countofanswers*80/$userscountall;
    }else{
        $answerpercent=80;
    }
    $precentresult+=$answerpercent;
    $user_role = (new \yii\db\Query())
    ->select(['item_name'])
    ->from('auth_assignment')
    ->where(['user_id'=>Yii::$app->user->getId()])
    ->all();
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
} elseif($user_role[0]['item_name']=='admin') {
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
    ];
    $menuItems[] = ['label' => 'Карта связи', 'url' => ['/site/select']];
    $menuItems[] = ['label' => 'Оценка коллег', 'url' => ['/site/contact']];
    $menuItems[] = ['label' => 'Статистика по вопросам', 'url' => ['/statis/index']];
    $menuItems[] = ['label' => 'Диаграмма', 'url' => ['/svaz/diagramm?questions=1&questions1=1&questions4=4&questions6=6&questions12=12&questions2=2&questions3=3&questions5=5&questions7=7&questions9=9&questions10=10']];
    $menuItems[] = ['label' => 'Пользователи', 'url' => ['/svaz/index']];
    $menuItems[] = ['label' => 'Вопросы', 'url' => ['/question/index']];
    $menuItems[] = ['label' => 'СКАД', 'url' => 'https://holotest.ru/scud/index.php'];
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
    $menuItems[] = '<li style="color: var(--bs-nav-link-hover-color);font-size:30px;margin-bottom:-20px;margin-top:-2px;"><p class="hover1">'.round($precentresult,1).'%</p><span class="hidden1">Проект пройден на: '.round($precentresult,1).'%<br>Карта связи: '.round($crosspercent,1).'%<br>Оценка пользователей: '.round($answerpercent,1).'%</span></li>';
}else{
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
        ['label' => 'Карта связи', 'url' => ['/site/select']],
        ['label' => 'Оценка коллег', 'url' => ['/site/contact']],
    ];
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выйти (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link logout']
        )
        . Html::endForm()
        . '</li>';
}
 
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['homeLink' => ['label' => 'Главная', 'url' => '/web/site/index'],'links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
