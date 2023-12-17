<?php

namespace app\db;

use DI\ContainerInterface;
use PDO;


/**
 * класс реализующий интерфейс по работе с базой данных
 */
class Db implements DbInterface
{

    public function __construct(private PDO $pdo) {}

    /**
     * Выборка из базы данных
     *
     * @param      string  $sql     The sql
     * @param      array   $params  The parameters
     *
     * @return     array   ( description_of_the_return_value )
     */
    public function select(string $sql, array $params=[]): array
    {
        $stmt = $this->pdo->prepare('select ' . $sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}