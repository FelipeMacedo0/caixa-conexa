<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load(); // Use safeLoad to prevent errors if the file is missing

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');


require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
