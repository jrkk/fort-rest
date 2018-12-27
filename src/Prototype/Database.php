<?php
namespace App\Prototype;

interface Database {
    public function retrive();
    public function update();
    public function persist();
    public function remove();
}