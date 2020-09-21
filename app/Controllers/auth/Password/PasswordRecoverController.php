<?php

namespace App\Controllers\Auth\Password;

use App\Controllers\Controller;
use App\Models\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;

class PasswordRecoverController extends Controller {

    protected $view;
    /**
     * @var Messages
     */
    protected $flash;
    /**
     * @var RouteParserInterface
     */
    protected $routeParser;
    /**
     * @var PHPMailer
     */
    protected $mail;

    public function __construct(Twig $view, Messages $flash,RouteParserInterface $routeParser, PHPMailer $mail)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
        $this->mail = $mail;
    }

    public function index(ServerRequestInterface $request,ResponseInterface $response)
    {
        return $this->view->render($response,'pages/auth/password/recover.twig');
    }

    public function action(ServerRequestInterface $request,ResponseInterface $response)
    {
        $data = $this->validate($request,[
            'email'=>['required','email']
        ]);

        $params = array_only($data, ['email']);

        if($user = User::whereEmail($params['email'])->first()){
            $reminder = Sentinel::getReminderRepository()->create($user);
        }

        $this->mail->addAddress($user->email);
        $this->mail->Subject = 'Changement de mot de passe';

        $this->mail->Body = $this->view->fetch('email/auth/password/recover.twig',[
            'user' => $user,
            'code'=> $reminder->code
        ]);

        $this->mail->send();
        $this->flash->addMessage('status', 'Email envoyé');



        return $response->withHeader(
            'Location',  $this->routeParser->urlFor('auth.password.recover')
            );
        }
}
