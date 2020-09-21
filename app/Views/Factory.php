<?php

namespace App\Views;

use Slim\Views\Twig;
use Twig\Loader\FilesystemLoader;

class Factory{

    protected $view;

    public static function getEngine(){

        return new Twig(new FilesystemLoader(__DIR__ . '/../../resources/views'),[
            'cache'=>false
        ]);
    }

    public function make($view = null, $data = []){

       return $this->view = static::getEngine()->fetch($view, $data);

    }

    public function render(){
        return $this->view;
    }
}
