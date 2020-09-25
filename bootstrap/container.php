<?php

use App\Providers\CsrfServiceProvider;
use App\Providers\EstateServiceProvider;
use App\Providers\ExchangeServiceProvider;
use App\Providers\FakerServiceProvider;
use App\Providers\FlashServiceProvider;
use App\Providers\MailServiceProvider;
use App\Providers\MiddlewareServiceProvider;
use App\Providers\PdfServiceProvider;
use App\Providers\RelationServiceProvider;
use App\Providers\ViewServiceProvider;

$container->addServiceProvider(new MiddlewareServiceProvider(
    $app->getRouteCollector()->getRouteParser()
));

$container->addServiceProvider(new ViewServiceProvider(
    $app->getRouteCollector()->getRouteParser()
));

$container->addServiceProvider(new FlashServiceProvider(

));

$container->addServiceProvider(new MailServiceProvider(

));

$container->addServiceProvider(new EstateServiceProvider(

));

$container->addServiceProvider(new ExchangeServiceProvider(

));

$container->addServiceProvider(new FakerServiceProvider(

));

$container->addServiceProvider(new CsrfServiceProvider(
    $app->getResponseFactory()
));

$container->addServiceProvider(new RelationServiceProvider(
));

$container->addServiceProvider(new PdfServiceProvider(
));

