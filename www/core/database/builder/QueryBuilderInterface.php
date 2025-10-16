<?php

namespace app\core\database\builder;

interface QueryBuilderInterface
{
    public function select(mixed ...$columns): static;
    public function from(string $table): static;
    public function where(...$args): static;
    public function insert(string $table, array $params);
    public function update(string $table, array $params);
    public function build(): string;
    public function delete(string $table): static;
    public function params(): ?array;
}
