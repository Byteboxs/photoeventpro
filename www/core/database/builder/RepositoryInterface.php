<?php

namespace app\core\database\builder;

interface RepositoryInterface
{
    public function all(): array;
    public function find(int|string $id): mixed;
    public function findWhere(array $data);
    public function create(array $data): mixed;
    public function update($data): mixed;
    public function delete($data): mixed;
    public function setQueryBuilder(QueryBuilderInterface $queryBuilder): void;
}
