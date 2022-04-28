<?php

ini_set('display_errors', '1');

require_once __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/../config/main.php';

$app = new Aigletter\Framework\Application($config);
$app->run();

