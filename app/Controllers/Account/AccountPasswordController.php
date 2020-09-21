<?php

namespace App\Controllers\Account;

use App\Controllers\Controller;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class AccountPasswordController extends Controller {

    protected $view;
    /**
     * @var Messages
     */
    protected $flash;
    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    public function __construct(Twig $view, Messages $flash,RouteParserInterface $routeParser)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
    }

    public function index(ServerRequestInterface $request,ResponseInterface $response)
    {
        return $this->view->render($response,'pages/account/password.twig');
    }

    public function action(ServerRequestInterface $request,ResponseInterface $response)
    {
        $data = $this->validate($request,[
            'current_password'=>['required', 'currentPassword'],
            'password'=>['required']
        ]);

        Sentinel::getUserRepository()->update(
            Sentinel::check(),
            array_only($data,['password'])
        );

        $this->flash->addMessage('status','Mot de passe mis à jour');
        return $response->withHeader('Location', $this->routeParser->urlFor('account.password'));
    }
}
