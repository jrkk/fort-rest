<?php
namespace App\Helpers;

trait Security {
    protected function isAllowedMethod(Request &$request, array $alllowedMethods = []) {
        if(empty($alllowedMethods)) return false;
        
    }
}