<?php


namespace App\Controllers\Message;


use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class MailboxController extends Controller
{
    /**
     * @var Twig
     */
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function getMailBox(ServerRequestInterface $request, ResponseInterface $response){
        return $this->view->render($response,'pages/mailbox/mailbox.twig');
    }

    public function getComposeMail(ServerRequestInterface $request, ResponseInterface $response){
        return $this->view->render($response,'pages/mailbox/compose.twig');
    }

    public function getReadMail(ServerRequestInterface $request, ResponseInterface $response){
        return $this->view->render($response,'pages/mailbox/read-mail.twig');
    }



}