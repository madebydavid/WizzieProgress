<?php

$app->mount('/', new Controller\Index());

$app->mount('/login', new Controller\Login());
$app->mount('/login/forgotten', new Controller\LoginForgotten());

$app->mount('/users', new Controller\Users());
$app->mount('/user', new Controller\User());

$app->mount('/students', new Controller\Students());
//$app->mount('/student', new Controller\Student());

$app->mount('/clubs', new Controller\Clubs());
$app->mount('/club', new Controller\Club());

