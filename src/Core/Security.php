<?php
namespace App\Core;

class Security {

    public function isAllowedMethod(Request &$request, array &$allowedMethods) {

        // if no allowed method request is strictly not served.
        if(count($allowedMethods) <= 0) 
            return false;

        $method = trim($request->getMethod(true));
        return ( $method != false 
            && $method != '' 
            && \in_array($method, $allowedMethods) );        
    }

    public function isAllowedOrigin(Request &$request, array &$origins) {

        // if origins are empty which will allow from any where.
        if(count($origins) > 0 || \in_array("*", $origins))
            return true;

        $referer = $request->getServer()->getReferer();    
        return ($referer != false 
            && \in_array($referer, $origins));

    }

    public function isAllowedProtocol(Request &$request, array &$schemes) {

        // if schemes are empty which will allow all schemes.
        if(\count($schemes) >  0) return true;


        $protocol = $request->getServer()->getProtocol();
        return ($protocol != false 
            && \in_array($protocol, $schemes));

    }

}