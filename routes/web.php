<?php

use App\Controllers\Account\AccountController;
use App\Controllers\Account\AccountPasswordController;
use App\Controllers\Ajax\ActionController;
use App\Controllers\Auth\Password\PasswordRecoverController;
use App\Controllers\Auth\Password\PasswordResetController;
use App\Controllers\Auth\SignInController;
use App\Controllers\Auth\SignOutController;
use App\Controllers\Auth\SignUpController;
use App\Controllers\Contract\ContractController;
use App\Controllers\Dashboard\EstateController;
use App\Controllers\Dashboard\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\Message\MailboxController;
use App\Controllers\Pages\ContactUsController;
use App\Controllers\Pages\Error404Controller;
use App\Controllers\Pages\PropertyListController;
use App\Controllers\Pages\SinglePropertyController;
use App\Controllers\User\UserController;
use App\Middleware\RedirectIfAuthenticated;
use App\Middleware\RedirectIfGuest;

$app->get('/', HomeController::class . ':getHome')->setName('home');

$app->get('/property_list', PropertyListController::class  . ':getPropertyList' )->setName('property_list');

$app->get('/contact', ContactUsController::class )->setName('contact_us');

$app->get('/404', Error404Controller::class)->setName('Error404');

$app->get('/single_property/{ref}', SinglePropertyController::class . ':getSingleProperty' )->setName('single_property');


$app->group('/auth',function ($route){

    $route->group('',function($route){
        $route->get('/signin', SignInController::class . ':index')->setName('auth.signin');
        $route->post('/signin', SignInController::class . ':action');

        $route->get('/signup', SignUpController::class . ':index')->setName('auth.signup');
        $route->post('/signup', SignUpController::class . ':action');

        $route->group('/password',function($route){
            $route->get('/recover', PasswordRecoverController::class . ':index')->setName('auth.password.recover');
            $route->post('/recover', PasswordRecoverController::class . ':action');

            $route->get('/reset', PasswordResetController::class . ':index')->setName('auth.password.reset');
            $route->post('/reset', PasswordResetController::class . ':action');
        });


    })->add(RedirectIfAuthenticated::class);

    $route->post('/signout', SignOutController::class)->setName('auth.signout');

});

$app->group('',function($route){
    $route->get('/account', AccountController::class . ':index')->setName('account');
    $route->post('/account', AccountController::class . ':action');

    $route->get('/account/password', AccountPasswordController::class . ':index')->setName('account.password');
    $route->post('/account/password', AccountPasswordController::class . ':action');

    $route->get('/dashboard', DashboardController::class)->setName('dashboard');

    $route->get('/add_estate', EstateController::class . ':getAddEstate')->setName('add_estate');
    $route->post('/add_estate', EstateController::class . ':postAddEstate');

    $route->get('/estate_user_list', EstateController::class . ':getEstateUserList')->setName('estate_user_list');
    $route->post('/estate_user_list', EstateController::class . ':postEstateUserList');

    $route->get('/estate_manager/{ref}', EstateController::class . ':getShowEstate')->setName('show_estate');
    $route->post('/estate_manager', EstateController::class . ':postShowEstate');

    $route->get('/mailbox', MailboxController::class . ':getMailBox')->setName('mailbox');
    $route->post('/mailbox', MailboxController::class . ':postMailBox');

    $route->get('/mailbox/compose', MailboxController::class . ':getComposeMail')->setName('compose_mail');
    $route->post('/mailbox/compose', MailboxController::class . ':postComposeMail');

    $route->get('/mailbox/read_mail', MailboxController::class . ':getReadMail')->setName('read_mail');
    $route->post('/mailbox/read_mail', MailboxController::class . ':postReadMail');


    $route->group('/users',function($route){
        $route->get('/new', UserController::class . ':getNewUser')->setName('create_user');
        $route->get('/my_list/{MySlug}/{relation_type}', UserController::class . ':getMyUsersList')->setName('my_list_of_users');

    });

    $route->group('/contracts',function($route){
        $route->get('/new', ContractController::class . ':getContractMain')->setName('contract_main');
    });


})->add(RedirectIfGuest::class);


//route pour ajax

$app->get('/action',ActionController::class . ':getAction')->setName('get_Action');
$app->post('/action',ActionController::class . ':postAction');



