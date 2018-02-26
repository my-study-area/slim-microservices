<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require './vendor/autoload.php';
$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) use ($app) {
    $response->getBody()->write("Ola Mundo!");
    return $response;
});

/**
* Lista de livros
**/
$app->get('/book', function (Request $request, Response $response) use ($app)
{
    $response->getBody()->write("lista de livros");
    return $response;
});

/**
 * Retornando por id
 */
$app->get('/book/{id}', function (Request $request, Response $response) use ($app)
{
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $response->getBody()->write("Exibindo o livro {$id}");
});

/**
* cadastra um novo livro
*/
$app->post('/book/', function(Request $request, Response $response) use ($app)
{
    $response->getBody()->write("cadastrando um livro");
    return $response;
});

/**
* Atualiza os dados do livro
*/
$app->put('/book/{id}', function(Request $request, Response $response)
{
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $response->getBody()->write("Atualizando o livro {$id}");
});

/**
* Deleta o livro pelo id
**/
$app->delete('/book/{id}', function (Request $request, Response $response) use ($app)
{
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $response->getBody()->write("Deleta o livro de id {$id}");
});


$app->run();
