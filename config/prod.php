<?php

// configure your app for the production environment

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
        'password' => 'CHANGEME'
    )
);
