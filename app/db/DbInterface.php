<?php

namespace app\db;

/**
 * Интерфейс для работы с базой даннных
 */
interface DbInterface
{
    /**
     * Выполнение выблоки (select)
     *
     * @param      string  $sql     текст запроса без слова SELECT
     * @param      array   $params  Параметры используемые в запросе ...
     */
    public function select(string $sql, array $params=[]): array;
}