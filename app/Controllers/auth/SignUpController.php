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
use App\App\RandomStringGenerator;

class SignUpController extends Controller {

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
        return $this->view->render($response,'pages/auth/signup.twig');
    }

    public function action(ServerRequestInterface $request,ResponseInterface $response)
    {
        // Create new instance of generator class.
        $generator = new RandomStringGenerator;

        // Set token length.
        $tokenLength = 24;

        // Call method to generate random string.
        $token = $generator->generate($tokenLength);


        $data = $this->validate($request, [
            'email' => ['required', 'email','emailIsUnique'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required'],
        ]);

        $data['slug'] = $token;

        try {
            $user = Sentinel::register(array_only($data,['email','first_name','last_name','password','slug']), true);
            Sentinel::authenticate(array_only($data,['email','password']));

        } catch (Exception $e) {
            $this->flash->addMessage('status', 'Wrong');
            return $response->withHeader(
                'Location', $this->routeParser->urlFor('auth.signup')
            );
        }
        return $response->withHeader(
            'Location', $this->routeParser->urlFor('dashboard')
        );
    }
}
