<?php


namespace App\Providers;


use App\App\EstateManagerInterface;
use App\Models\Estate;
use League\Container\ServiceProvider\AbstractServiceProvider;


class EstateServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'estate'
    ];
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('estate', function (){
           return new EstateManagerInterface();
        });


    }

}