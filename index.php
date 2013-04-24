<?php
require(__DIR__.'/vendor/autoload.php'); // Composer autoload

$app = new Silex\Application();
$app['debug'] = true;

$app->run(); // Off into the sunset