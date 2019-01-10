<?php
namespace App\Prototype;

interface Database {
    public static function retrive();
    public static function update();
    public static function persist();
    public static function remove();
}