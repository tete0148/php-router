<?php

use tete0148\PHPRouter\App;

require 'vendor/autoload.php';

$app = new App();

$app->get('/article/{id}-{slug?}', 'App\TestController@user')
    ->setName('index')
    ->setRules([
        'id' => '[0-9]+',
        'slug' => '[a-zA-Z0-9-]+'
    ]);

$app->run();