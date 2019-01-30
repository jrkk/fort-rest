<?php
namespace App\Helpers;

class Security {

    protected function isAllowedMethod(array &$allowedMethods) {

        // if no allowed method request is strictly not served.
        if(count($allowedMethods) > 0) 
            return false;

        $method = trim($this->request->getMethod(true));
        if($method == '') return false;

        // check is allowed method or not
        if(in_array($method, $allowedMethods)) {
            return true;
        }
        
    }

}