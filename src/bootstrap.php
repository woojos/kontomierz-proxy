<?php
use woojos\kontomierzproxy\KontomierzProxyApp;

require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__ .'/../config/config.php';

$app = new KontomierzProxyApp();
$app['debug'] = true;
$app['apiKey'] = $apiKey;

$app->register(
    new Silex\Provider\TwigServiceProvider(),
    ['twig.path' => __DIR__.'/../public/views']
);
