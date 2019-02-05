<?php
namespace App\Helper;

trait Oauth {
    protected function isAuthorized() {
        switch(RestConfig::Authorization) {
            case 'basic' : 
            case 'bearer' : 
        }
    }
    protected function getUser() {

    }

    protected function validateBasicAuthorization() {

    }
    protected function validateBearerAuthorization() {
        
    }
}