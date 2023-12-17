<?php

namespace app;

use DI\Container;
use PDO;
use Slim\Exception\HttpNotFoundException;
use M1\Env\Parser;

use app\db\Db;
use app\db\DbInterface;


class Di
{
    private static $di;

    public function __construct()
    {
        $containerConfig = [
            'db.dsn' => function($c) {
                return sprintf('pgsql:host=db;port=%d;dbname=%s', $c->get('db_port'), $c->get('db_name'));
            },
            DbInterface::class => function($c) {
                return new Db(new PDO($c->get('db.dsn'), $c->get('db_user'), $c->get('db_pass')));
            },
        ];
        $path = realpath(__DIR__.'/../.env');
        if ($path) {
            $containerConfig = array_merge(Parser::parse(file_get_contents($path)), $containerConfig);
        }

        // $parser = Parser::parse(file_get_contents('.env');
        static::$di = new Container($containerConfig);
    }

    /**
     * получить объект DI контейнера ...
     *
     * @return     DI\Container  The di container.
     */
    public static function getDiContainer(): Container
    {
        return static::$di;
    }
}


new Di();
