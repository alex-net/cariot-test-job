<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Exception\HttpNotFoundException;

use app\models\Book;
use app\Di;


// var_dump($container);

$app = AppFactory::create();

$app->get('/', function (Request $rq, Response $rs) {
    $rs->getBody()->write('<a href="/books">Книги</a>');
    return $rs;
});

/**
 * Выборка списка книг с пагинацией
 */
$app->get('/books', function (Request $rq, Response $rs) {
    $rs = $rs->withAddedHeader('Content-type', 'application/json');
    $params = $rq->getQueryParams();
    $rs->getBody()->write(json_encode([
        'items' => Book::getAll(
            intval($params['p'] ?? 1),
            intval($params['pp'] ?? 2)
        ),
    ]));
    return $rs;
});


/**
 * обработчик запросов для выборки одной книги .. и обновления одной книги ...
 *
 * @param      Request   $rq      The request
 * @param      Response  $rs      { parameter_description }
 * @param      array     $params  The parameters
 *
 * @return     Response  ( description_of_the_return_value )
 */
$handleGetPost = function (Request $rq, Response $rs, array $params)
{
    $rs = $rs->withAddedHeader('Content-type', 'application/json');
    $b = Book::getOne(intval($params['id']));
    if (!$b) {
        $rs = $rs->withStatus(404);
        $rs->getBody()->write(json_encode([
            'error' => 'Запись не найдена',
        ]));
        return $rs;
    }
    switch (strtolower($rq->getMethod())) {
        case 'get':
            $rs->getBody()->write(json_encode($b->attributes));
            break;
        case 'post':
            $data = json_decode((string)$rq->getBody(), 1);
            $b->attributes = $data;
            // тут должно быть валидация и сохранение .. .
            $rs->getBody()->write(json_encode([
                'post' => $data,
                'attrs' => $b->attributes,
            ]));
    }

    return $rs;
};

$app->get('/books/{id:[0-9]+}', $handleGetPost);

$app->post('/books/{id:[0-9]+}', $handleGetPost);

$app->run();


