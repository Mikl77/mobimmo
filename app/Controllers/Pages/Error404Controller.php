<?php


namespace App\Controllers\Pages;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class Error404Controller
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request,ResponseInterface $response)
    {
        return $this->view->render($response,'pages/errors/404.twig');
    }

}