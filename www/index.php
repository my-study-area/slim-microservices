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
    $return = $response->withJson(['msg' => 'Lista de Livros'], 200)
        ->withHeader('Content-type', 'application/json');
    return $return;
});

/**
 * Retornando por id
 */
$app->get('/book/{id}', function (Request $request, Response $response) use ($app)
{
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $return = $response->withJson(['msg' => "Exibindo o livro {$id}"], 200)
        ->withHeader('Content-type', 'apllication/json');
    return $return;
});

/**
* cadastra um novo livro
*/
$app->post('/book/', function(Request $request, Response $response) use ($app)
{
    $return = $response->withJson(['msg' => 'Cadastrando um livro'], 201)
        ->withHeader('Content-type', 'application/json');
    return $return;
});

/**
* Atualiza os dados do livro
*/
$app->put('/book/{id}', function(Request $request, Response $response)
{
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $return = $response->withJson(['msg' => "Atualizando o livro {$id}"], 200)
        ->withHeader('Content-type', 'application/json');
    return $return;
});

/**
* Deleta o livro pelo id
**/
$app->delete('/book/{id}', function (Request $request, Response $response) use ($app)
{
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');
    $return = $response->withJson(['msg' => 'Deletando o livro de id {$id}'], 200)
        ->withHeader('Contetent-type', 'application/json');
    return $return;
});


$app->run();
