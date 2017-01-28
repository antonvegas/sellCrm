<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    NavBar::begin([
        'brandLabel' => '<div class="block_logo"><img src="/images/logo.png"><div class="logo_style">Vape-Tabakof.ru</div></div>',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-custom navbar-fixed-top',

        ],
    ]);

    $settingsMenu = [];

    if(Yii::$app->user->can('backend/product'))
        $settingsMenu = array_merge($settingsMenu, [
            '<li class="divider"></li>',
            '<li class="dropdown-header">Управление товарами</li>',
            [
                'label' => 'Товары',
                'url' => '/backend/product/index'
            ],
            [
                'label' => 'Категории товаров',
                'url' => '/backend/category/index'
            ],
            [
                'label' => 'Атрибуты товаров',
                'url' => '/backend/product-attribute/index'
            ]
        ]);

    if(Yii::$app->user->can('settings/users'))
        $settingsMenu = array_merge($settingsMenu, [
            '<li class="divider"></li>',
            '<li class="dropdown-header">Управление Пользователями</li>',
            [
                'label' => 'Пользователи',
                'url' => '/settings/users'
            ],
        ]);

    if(Yii::$app->user->can('settings/access'))
        $settingsMenu = array_merge($settingsMenu, [
            '<li class="divider"></li>',
            '<li class="dropdown-header">Настройки доступа</li>',
            [
                'label' => 'Управление ролями',
                'url' => '/settings/access/role'
            ],
            [
                'label' => 'Управление правилами',
                'url' => '/settings/access/permission'
            ]
        ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [

            (Yii::$app->user->can('backend/sale')) ?
                [
                    'label' => 'Менеджер',
                    'items' =>
                        [
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Раздел продаж</li>',
                            [
                                'label' => 'Список продаж',
                                'url' => '/backend/sale/index'
                            ],
                            [
                                'label' => 'Новая продажа',
                                'url' => '/backend/sale/add'
                            ],
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Отгрузка товара</li>',
                            [
                                'label' => 'Закупки',
                                'url' => '/backend/purchase/index'
                            ]
                        ]
                ]
                : '',

            (Yii::$app->user->can('backend/stock')) ?
                [
                    'label' => 'Склад',
                    'url' => '/backend/stock/index'
                ]
                :
                '',

            (!Yii::$app->user->isGuest && ( ! empty($settingsMenu) )) ?
                [
                    'label' => 'Настройки системы',
                    'items' => $settingsMenu
                ]
                : '',

            Yii::$app->user->isGuest ? (
            ['label' => 'Войти', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    [
                        'class' => 'btn btn-link',
                        'style' => 'padding-top: 15px;padding-bottom: 15px;',
                    ]
                )
                . Html::endForm()
                . '</li>'
            )
        ],

    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Vape-Tabakof.ru <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
