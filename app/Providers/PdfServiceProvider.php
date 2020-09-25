<?php


namespace App\Providers;


use App\App\PdfInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;


class PdfServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'pdf'
    ];
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('pdf', function (){
            return new PdfInterface();
        });


    }

}