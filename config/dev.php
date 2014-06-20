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
    ),
    'email.options' => array(
        'sender' => 'no-reply+wizziewizzie.app@gmail.com'
    ),
    'swiftmailer.options' => array(
        'host' => 'smtp.gmail.com',
        'port' => '465',
        'username' => 'wizziewizzie.app@gmail.com',
        'password' => '',
        'encryption' => 'ssl',
        'auth_mode' => null
    )
);
