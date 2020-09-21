<?php

session_start();

use App\Providers\ViewServiceProvider;
use App\Views\Factory;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use League\Container\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

Sentinel::instance(
    new SentinelBootstrapper(
        require(__DIR__ . '/../config/auth.php')
    )
);
LengthAwarePaginator::viewFactoryResolver(function(){
    return new Factory();
});

LengthAwarePaginator::defaultView('components/pagination/default_pagination.twig');

Paginator::currentPathResolver(function (){
    return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
});

Paginator::currentPageResolver(function (){
    if(isset($_GET['page'])){
        return $_GET['page'];
    }

});

require __DIR__ . '/database.php';

$container =  new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

require __DIR__ . '/container.php';
require __DIR__ . '/middleware.php';
require __DIR__ . '/controllers.php';
require __DIR__ . '/validation.php';
require __DIR__ . '/exceptions.php';
require __DIR__ . '/../routes/web.php';
