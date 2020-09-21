<?php


use App\App\RandomStringGenerator;
use App\Controllers\Account\AccountController;
use App\Controllers\Account\AccountPasswordController;
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


$container->add(HomeController::class, function() use ($container){
    return new HomeController(
        $container->get('view'),
        $container->get('estate'),
        $container->get('faker')
    );
});

$container->add(MailboxController::class, function() use ($container){
    return new MailboxController(
        $container->get('view')
    );
});

$container->add(PropertyListController::class, function() use ($container){
    return new PropertyListController(
        $container->get('view'),
        $container->get('estate')
    );
});

$container->add(ContactUsController::class, function() use ($container){
    return new ContactUsController(
        $container->get('view')
    );
});

$container->add(Error404Controller::class, function() use ($container){
    return new Error404Controller(
        $container->get('view')
    );
});

$container->add(SinglePropertyController::class, function() use ($container){
    return new SinglePropertyController(
        $container->get('view'),
        $container->get('estate')
    );
});

$container->add(DashboardController::class, function() use ($container){
    return new DashboardController(
        $container->get('view'),
        $container->get('estate'),
        $container->get('exchange')
    );
});

$container->add(EstateController::class, function() use ($app,$container){
    return new EstateController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser(),
        $container->get('estate')
    );
});

$container->add(SignInController::class, function() use ($app,$container){
    return new SignInController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(SignUpController::class, function() use ($app,$container){
    return new SignUpController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(AccountController::class, function() use ($app,$container){
    return new AccountController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(AccountPasswordController::class, function() use ($app,$container){
    return new AccountPasswordController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(PasswordRecoverController::class, function() use ($app,$container){
    return new PasswordRecoverController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser(),
        $container->get('mail')
    );
});

$container->add(PasswordResetController::class, function() use ($app,$container){
    return new PasswordResetController(
        $container->get('view'),
        $container->get('flash'),
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(SignOutController::class, function() use ($app){
    return new SignOutController(
        $app->getRouteCollector()->getRouteParser()
    );
});

$container->add(UserController::class, function() use ($container){
    return new UserController(
        $container->get('view'),
        $container->get('relation')
    );
});

$container->add(ContractController::class, function() use ($container){
    return new ContractController(
        $container->get('view'),
        $container->get('relation'),
        $container->get('estate')
    );
});

$container->add(RandomStringGenerator::class, function () use ($container){
    return new RandomStringGenerator();
});

