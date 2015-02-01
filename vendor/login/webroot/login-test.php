<?php
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential settings.
require __DIR__.'/config_with_app.php';


// Create services and inject into the app. 
$di  = new \Anax\DI\CDIFactoryDefault();

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

$di->set('LoginController', function() use ($di) {
    $controller = new \Anax\Login\LoginController();
    $controller->setDI($di);
    return $controller;
});

$app = new \Anax\Kernel\CAnax($di);

// Home route
$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Welcome to Anax Loginsystem");
    $app->views->add('login/index');

    $loggedIn = $app->__get("LoginController")->isLoggedInAction();

     //UNCOMMENT IF YOU NEED TO CREATE THE DATABASE
    //require_once("setup-database.php");

    if($loggedIn) {
        $app->dispatcher->forward([
            'controller' => 'login',
            'action'     => 'view',
        ]);
    } else {
        $app->views->add('login/form', [
            'output' => null
        ]);
    }
});


// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the page
$app->theme->render();
