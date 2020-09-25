<?php


namespace App\Controllers;


use League\Container\Container;

class Base_controller
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

    }


}