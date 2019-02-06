<?php
namespace App\Prototype;

interface Authorization {
    public function setSecureKey(string $key);
    public function generateToken(string $info);
    public function refreshToken(string $refreshToken);
    public function setAccessToken(string $token);
    public function getTokenData(); 
    public function isAuthorized();
}