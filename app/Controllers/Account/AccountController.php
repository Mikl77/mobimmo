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

class AccountController extends Controller {

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
        return $this->view->render($response,'pages/account/index_admin.twig',[
            'current_page'=>'account'
        ]);
    }

    public function action(ServerRequestInterface $request,ResponseInterface $response)
    {
        $data = $this->validate($request,[
            'email'=> ['required','email','emailIsUnique'],
            'first_name'=>['required'],
            'last_name'=>['required']
        ]);

        Sentinel::check()->update(
            array_only($data,[
                'email','first_name','last_name'
            ]));
        $this->flash->addMessage('status','Compte mis à jour');
        return $response->withHeader('Location', $this->routeParser->urlFor('account'));
    }
}
