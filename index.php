<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Application;

$app = new Application();
$app->run();

// Я бы лучше использовал закрывающий тег. Хоть можно и без него, я бы все же использовал
