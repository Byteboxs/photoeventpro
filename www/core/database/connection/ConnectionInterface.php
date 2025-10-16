<?php

namespace app\core\database\connection;

interface ConnectionInterface
{
    public function connect(): void;
    public function disconnect(): void;
    public function isConnected(): bool;
    public function getConnection(): mixed;
    public function run($sql, $args = NULL): ?\PDOStatement;
}
