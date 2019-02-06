<?php
namespace App\Helper;

use App\Config\RestConfig;

trait Oauth {
    protected function isAuthorized() {
        switch(RestConfig::Authorization) {
            case 'basic' :
                break;
            case 'bearer' : 
                break;
        }
    }
    protected function getUser() {

    }
}