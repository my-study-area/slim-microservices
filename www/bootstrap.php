<?php
require './vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Psr7Middlewares\Middleware\TrailingSlash;
use Monolog\Logger;

$configs = [
    'setting' => [
        'displayErrordetails' => true,
    ]
];

$container = new \Slim\Container($configs);

$container['errorHandler'] = function ($c)
{
    return function ($request, $response, $exception) use ($c)
    {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $c['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(["message" => $exception->getMessage()], $statusCode);
    };
};

$container['logger'] = function ($container)
{
    $logger = new Monolog\Logger('books-microservice');
    $logfile = __DIR__ . '/logs/books-microservice.log';
    $stream = new Monolog\Handler\StreamHandler($logfile, Monolog\Logger::DEBUG);
    $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
        $stream, Monolog\Logger::INFO);
    $logger->pushHandler($fingersCrossed);
    return $logger;
};

$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'Application/json')
            ->withHeader("Access-Control-Allow-Methods", implode(",", $methods))
            ->withJson(["message" => "Method not Allowed; Method must be one of: " . implode(', ', $methods)], 405);
    };
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(['message' => 'Page not found']);
    };
};

$isDevMode = true;


$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src/Models/Entity"), $isDevMode);

$conn = array(
    'host' => '192.168.99.100',
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'db',
);

$entityManager = EntityManager::create($conn, $config);

$container['em'] = $entityManager;



$app = new \Slim\App($container);
$app->add(new TrailingSlash(false));
