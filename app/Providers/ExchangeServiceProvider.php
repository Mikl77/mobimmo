<?php


namespace App\Providers;


use App\App\ExchangeManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;


class ExchangeServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'exchange'
    ];
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('exchange', function (){
           return new ExchangeManagerInterface();
        });


    }

}