<?php

namespace App\Controllers;

use App\App\FakerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class HomeController{

    protected $view;

    protected $estate;
    /**
     * @var FakerInterface
     */
    private $faker;

    public function __construct(Twig $view, $estate, FakerInterface $faker)
    {
        $this->view = $view;
        $this->estate = $estate;
        $this->faker = $faker;
    }

    public function getHome(ServerRequestInterface $request,ResponseInterface $response)
    {
        //$this->faker->GenerateFake('estates','100');
        return $this->view->render($response,'pages/home/home.twig',[
            'newest_properties'=> $this->estate->getNewestEstates(),
            'sold_estates'=>$this->estate->getSoldEstates()
    ]);
}
}
