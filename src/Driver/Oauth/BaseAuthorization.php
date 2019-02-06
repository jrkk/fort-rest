<?php
namespace App\Driver\Oauth;

use App\Prototype\Authorization;

class BaseAuthorization implements Authorization,Serializable
{
    protected $key = '';
    protected $state = false;
    protected $data = [];

    public function setSecureKey(string $key)
    {
        if(empty($key))
            throw new Exception('Empty Secure key can`t be processed');
        
        $this->key = $key;
    }
    public function generateToken(string $info) {

    }
    public function refreshToken(string $refreshToken, string $token) {

    }
    public function setAccessToken(string $token)
    {

    }
    public function getTokenData()
    {

    }
    public function isAuthorized() {
        return $this->state;
    }
}
