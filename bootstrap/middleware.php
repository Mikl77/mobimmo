<?php

use App\Middleware\FlashOldFormData;

$app->add(new FlashOldFormData(
    $container->get('flash')
));

$app->add('csrf');
