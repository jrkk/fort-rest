<?php
namespace App\Prototype;

interface Database {
    public static function retrive(QueryBuilder $query);
    public static function update(QueryBuilder $query);
    public static function persist(QueryBuilder $query);
    public static function remove(QueryBuilder $query);
}