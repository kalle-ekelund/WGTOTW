<?php
require __DIR__.'/config_with_app.php';

$app->session();

$di->set('form', 'Mos\HTMLForm\CForm');

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

$di->set('FormController', function() use ($di) {
    $controller = new \Anax\HTMLForm\FormController();
    $controller->setDI($di);
    return $controller;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('LoginController', function() use ($di) {
    $controller = new \Anax\Login\LoginController();
    $controller->setDI($di);
    return $controller;
});

$di->set('QuestionController', function() use ($di) {
    $controller = new \Anax\WGTOTW\QuestionController();
    $controller->setDI($di);
    return $controller;
});

$di->set('AnswerController', function() use ($di) {
    $controller = new \Anax\WGTOTW\AnswerController();
    $controller->setDI($di);
    return $controller;
});

$di->set('CommentController', function() use ($di) {
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');

$app->router->add('', function() use ($app) {
    $app->theme->setTitle("Home");
/*
    $content = $app->fileContent->get('home.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content,
    ]);*/

    $app->views->add('tags/list-popular');
    $app->views->add('questions/list-recent');
    $app->views->add('users/list-ranking');

});

$app->router->add('about', function() use ($app) {
    $app->theme->setTitle("About us");

    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content
    ]);

});

$app->router->add('source', function() use ($app) {
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);

});

$app->router->add('tags', function() use ($app) {
    $app->theme->addStylesheet('css/questions.css');
    $app->theme->setTitle("Tags");

    $app->views->add('tags/list-all');

});

$app->router->handle();
$app->theme->render();