<?php
namespace App\Prototype;

interface QueryBuilder {
    public function from(string $table, string $alias = '') ;
    public function join(string $table, string $alias , string $on , string $join_type = '=' ) ;
    public function orderBy(string $columnsList, string $order = 'ASC' );
    public function limit(int $limit = 1000, int $offset = 0);
    public function where(string $column , $value, string $operand = "=");
    public function whereOr(string $column , $value, string $operand = "=");
    public function whereIn(string $column, array $assoc);
    public function whereNotIn(string $column, array $assoc);
    public function groupBy(string $column);
    public function having(string $column , $value, string $operand = "=");
    public function set(string $column, $value );
    public function select(array $fields = ["*"], bool $mode = false);
    public function insert(string $table, array $data = []);
    public function update(string $table, array $data = []);
    public function delete(string $table = '');
    public function getQuery();
    public function getBindParamsFormat();
    public function getBindPrams();
    public function flush();
}