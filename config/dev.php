<?php

use Silex\Provider\MonologServiceProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/silex_dev.log',
));

$app['config'] = array(
    'js_options' => array(
    ),
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'dbname'    => 'wizzieprogress',
        'user'        => 'root',
        'password'    => ''
    ),
    'admin.options' => array(
        'username' => 'admin',
        'password' => 'david'
    )
);
