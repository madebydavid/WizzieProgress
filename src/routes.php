<?php

$app->mount('/', new Controller\Index());
$app->mount('/login', new Controller\Login());

$app->mount('/error', new Controller\Error());

$app->mount('/users', new Controller\Users());
//$app->mount('/user', new Controller\User());

$app->mount('/students', new Controller\Students());
//$app->mount('/student', new Controller\Student());

$app->mount('/Locations', new Controller\Locations());
//$app->mount('/location', new Controller\Location());

