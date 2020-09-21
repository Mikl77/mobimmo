<?php

namespace App\Controllers\Auth\Password;

use App\Controllers\Controller;
use App\Models\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class PasswordResetController extends Controller {

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
        $params = array_only($request->getQueryParams(),['email','code']);

        if(!$this->activationCodeExists(
            $user = User::whereEmail($email = $params['email'] ?? null)->first(),
            $code = $params['code'] ?? null)
        ){
            return $response->withHeader(
                'Location',  $this->routeParser->urlFor('home')
            );
        }

        return $this->view->render($response,'pages/auth/password/reset.twig',compact('email','code'));
    }

    public function action(ServerRequestInterface $request,ResponseInterface $response)
    {
        $data = $this->validate($request,[
            'password'=>['required']
        ]);

        $params = array_only($data, ['email','code','password']);

        if(!$this->activationCodeExists(
            $user = User::whereEmail($params['email'] ?? null)->first(),
            $code = $params['code'] ?? null)
        ){
            return $response->withHeader(
                'Location',  $this->routeParser->urlFor('home')
            );
        }

        Sentinel::getReminderRepository()->complete($user,$code,$params['password']);

        $this->flash->addMessage('status','Mot de passe mis à jour');

        return $response->withHeader('Location',$this->routeParser->urlFor('auth.signin'));
        }


        protected function activationCodeExists(?User $user, $code){

            if(!$user){
                return false;
            }

            if(!Sentinel::getReminderRepository()->exists($user,$code)){
                return false;
            }

            return true;

        }
}
