<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Book as Book;

require 'bootstrap.php';

$app->get('/', function (Request $request, Response $response) use ($app) {
    $response->getBody()->write("Ola Mundo!");
    return $response;
});

/**
* Lista de livros
**/
$app->get('/book', function (Request $request, Response $response) use ($app)
{
    $entityManager = $this->get('em');
    $bookRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $books = $bookRepository->findAll();

    $return = $response->withJson($books, 200)
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

    $entityManager = $this->get('em');
    $bookRepository = $entityManager->getRepository('App\models\Entity\Book');
    $book = $bookRepository->find($id);

    if (!$book) {
        $logger = $this->get('logger');
        $logger->warning("Book {$id} Not Found");
        throw new \Exception("Book not found", 404);
    }

    $return = $response->withJson($book, 200)
        ->withHeader('Content-type', 'application/json');
    return $return;
});

/**
* cadastra um novo livro
*/
$app->post('/book', function(Request $request, Response $response) use ($app)
{
    $params = (object) $request->getParams();

    $entityManager = $this->get('em');

    $book = (new Book())->setName($params->name)->setAuthor($params->author);

    $entityManager->persist($book);
    $entityManager->flush();


    $logger = $this->get('logger');
    $logger->info('Book Created!', $book->getValues());

    $return = $response->withJson($book, 201)
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
    $name = $request->getParam('name');
    $author = $request->getParam('author');

    $entityManager = $this->get('em');
    $bookRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $bookRepository->find($id);

    $logger = $this->get('logger');

    if (!$book) {
        $logger->warning("Book {$id} Not Found - Impossible to Update");
        throw new \Exception("Book not found", 404);
    }

    $book->setName($name)->setAuthor($author);

    $entityManager->persist($book);

    $entityManager->flush();

    $logger->info("Book {$id} updated!", $book->getValues());

    $return = $response->withJson( $book, 200)
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

    $logger = $this->get('logger');

    $entityManager = $this->get('em');
    $bookRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $bookRepository->find($id);

    if (!$book) {
        $logger->info("Book {$id} deleted", $book->getValues());
        $logger->info("Book {$id} not Found");
        throw new \Exception("Book not found", 404);
    }

    $entityManager->remove($book);
    $entityManager->flush();

    $return = $response->withJson(['msg' => "Deletando o livro de id {$id}"], 204)
        ->withHeader('Contetent-type', 'application/json');
    return $return;
});

$app->run();
