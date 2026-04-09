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
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '<img src="' . Yii::getAlias('@web') . '/images/logo-stockwise.png" alt="Stockwise logo" style="height: 30px; margin-right: 10px;">',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-light bg-light fixed-top border-bottom']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login']]
                : '<li class="nav-item d-flex align-items-center text-dark pe-2">'
                    . '<span class="me-3">' . Html::encode(Yii::$app->user->identity->name) . '</span>'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex m-0'])
                    . Html::submitButton(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right me-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg> Sair',
                        ['class' => 'btn btn-sm btn-outline-dark d-flex align-items-center', 'title' => 'Sair do sistema']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<div class="d-flex flex-grow-1" style="padding-top: 56px;">
    <!-- Sidebar -->
    <aside class="bg-light border-end" style="width: 250px; min-height: calc(100vh - 56px - 60px);">
        <?php
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
        ];
        
        if (!Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Products', 'url' => ['/site/products']];
            $menuItems[] = ['label' => 'Sales', 'url' => ['/site/sales']];
        }

        echo Nav::widget([
            'options' => ['class' => 'nav flex-column nav-pills p-3'],
            'items' => $menuItems
        ]);
        ?>
    </aside>

    <!-- Main Content -->
    <main id="main" class="flex-grow-1 p-4" role="main" style="overflow-y: auto;">
        <div class="container-fluid">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>
</div>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">
                <img src="<?= Yii::getAlias('@web') ?>/images/logo-stockwise.png" alt="Stockwise logo" style="height: 20px; margin-right: 5px;">
                &copy; Stockwise <?= date('Y') ?>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
