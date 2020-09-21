<?php


namespace App\Providers;



use App\App\RelationManagerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class RelationServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'relation'
    ];
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('relation', function (){
            return new RelationManagerInterface();
        });


    }

}