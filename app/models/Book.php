<?php

namespace app\models;

use app\Di;
use app\db\DbInterface;
use DI\Container;

/**
 * Класс для работы с книгами ..
 *
 */
class Book
{
    protected string $name, $author, $date, $descr;
    protected int $pages;


    public function __construct(
        private DbInterface $db,
        array $attrs = []
    ) {
        foreach (array_keys(get_class_vars(static::class)) as $key) {
            if (!empty($attrs[$key])) {
                $this->$key = $attrs[$key];
            }
        }
    }

    /**
     * Получить все записи книжек
     *
     * @param      int    $page   номер страницы
     * @param      int    $pp     число записей на страницу
     *
     * @return     array  All.
     */
    public static function getAll(int $page=1, int $pp=2): array
    {
        $connection = Di::getDiContainer()->get(DbInterface::class);
        $p = max(($page - 1) * $pp, 0);
        $list = $connection->select("* from books limit $pp offset $p");
        return $list;
    }


    public static function getOne(int $id): ?Book
    {
        $container = Di::getDiContainer();

        $connection = $container->get(DbInterface::class);
        $list = $connection->select('* from books where id = :id', [':id' => $id]);
        if (!$list) {
            return null;
        }
        return $container->make(static::class, ['attrs' => $list[0]]) ;
    }

    /**
     * Вернуть доступные поля книги с запонением
     *
     * @return     <type>  The attributes.
     */
    public function getAttributes(): array
    {
        $list = [];
        foreach ($this->getAllowedAttrs() as $key) {
            $list[$key] = $this->$key ?? null;
        }
        return $list;
    }

    /**
     * Установить новые значения для полей книги
     *
     * @param      array  $val    The value
     */
    public function setAttributes(array $val)
    {
        foreach ($this->getAllowedAttrs() as $key) {
            if (isset($val[$key])) {
                $this->$key = $val[$key];
            }
        }
    }


    public function __get($name)
    {
        $getMethod = 'get' . ucfirst($name);
        if (method_exists($this, $getMethod)) {
            return $this->$getMethod();
        }
    }


    public function __set($name, $val)
    {
        $setMethod = 'set' . ucfirst($name);
        if (method_exists($this, $setMethod)) {
            $this->$setMethod($val);
        }
    }

    /**
     * перечисление всех доступных полей .. в книге ...
     *
     * @return     <type>  The allowed attributes.
     */
    private function getAllowedAttrs(): array
    {
        $list = [];
        foreach (array_keys(get_class_vars(static::class)) as $key) {
            if ($key == 'db') {
                continue;
            }
            $list[] = $key;
        }
        return $list;
    }

}