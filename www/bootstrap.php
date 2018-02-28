<?php
require './vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

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
