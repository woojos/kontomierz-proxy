<?php
use woojos\kontomierzproxy\web\IndexController;

require_once __DIR__ . '/../src/bootstrap.php';


$app->get('/', function() use($app) {
    return (new IndexController($app))->show();
});

$app->post('/', function() use($app) {
    return (new IndexController($app))->pushToKontomierz();
});

$app->run();