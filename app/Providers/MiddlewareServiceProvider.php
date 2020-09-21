<?php


namespace App\Providers;


use App\Middleware\RedirectIfAuthenticated;
use App\Middleware\RedirectIfGuest;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;

class MiddlewareServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        RedirectIfGuest::class,
        RedirectIfAuthenticated::class
    ];

    protected $routeParser;

    public function __construct(RouteParserInterface $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function register()
    {
        $container = $this->getContainer();

        $container->add(RedirectIfGuest::class,function() use ($container){
            return new RedirectIfGuest(
                $container->get('flash'),
                $this->routeParser
            );
        });

        $container->add(RedirectIfAuthenticated::class,function(){
            return new RedirectIfAuthenticated(
                $this->routeParser
            );
        });
    }
}
