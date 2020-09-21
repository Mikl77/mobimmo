<?php

namespace App\Providers;


use League\Container\ServiceProvider\AbstractServiceProvider ;
use PHPMailer\PHPMailer\PHPMailer;


class MailServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'mail'
    ];
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('mail', function (){
            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth=true;
            $mail->Username='6a344b1d966760';
            $mail->Password='1bca2798e458a1';
            $mail->Port=2525;

            $mail->setFrom('mickaelpenhoat@gmail.com','Mike');
            $mail->isHTML();

            return $mail;
        });
    }
}