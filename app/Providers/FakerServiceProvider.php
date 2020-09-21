<?php


namespace App\Providers;


use App\App\FakerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;


class FakerServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'faker'
    ];
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('faker', function (){
           return new FakerInterface();
        });


    }

}