<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class SignInController extends Controller {

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
        return $this->view->render($response,'pages/auth/signin.twig', [
            'redirect'=>$request->getQueryParams()['redirect'] ?? null
        ]);
    }

    public function action(ServerRequestInterface $request,ResponseInterface $response)
    {
        $data = $this->validate($request,[
            'email'=>['email','required'],
            'password'=>['required']
        ]);

        try{
            if(!$user = Sentinel::authenticate(
                array_only($data,['email','password']),
                isset($data['persist']))){
                throw new Exception('E-mail ou Mdp incorrect');
            }

        }catch (Exception $e){
            $this->flash->addMessage('status',$e->getMessage());
            return $response->withHeader(
                'Location',$this->routeParser->urlFor('auth.signin'));
        }

        return $response->withHeader(
            'Location', $data['redirect'] ?  $data['redirect'] : $this->routeParser->urlFor('home')
            );
        }
}
