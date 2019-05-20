<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['projectDaoInterface'] = function ($c) {
    $dbConnection = new PDO("mysql:host=localhost;dbname=desarrolloweb","root","");
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $dbConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    return new \Project\Utils\MySqlProjectDaoInterface($dbConnection);
};

$container['authentication'] = function ($c){
    return new Slim\Middleware\JwtAuthentication(
    [
        "secure" => false,
        "secret" => 'my secret',
    ]);
};
