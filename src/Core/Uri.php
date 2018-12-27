<?php
namespace App\Core;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface {

    protected $scheme = '';
    protected $credentials = [
        'username' => '',
        'password' => ''
    ]; 
    protected $host = '';
    protected $port = 80;
    protected $path = '';
    protected $query = '';
    protected $fragment = '';

    private $url = '';
    function __construct($url = '') {
        System::log("info", "Uri Class intiated");
        if(empty($url) || $url === '' ) {
            $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https';
            $this->url = "{$scheme}://{$_SERVER['HTTP_HOST']}:{$_SERVER['SERVER_PORT']}{$_SERVER['REQUEST_URI']}";
        }
    }
    public function getScheme() {
        return self::withScheme(
            parse_url($this->url, PHP_URL_SCHEME)
        )->scheme;
    }
    public function getAuthority() {

    }
    public function getUserInfo() {
        $this->withUserInfo(
            parse_url($this->url, PHP_URL_USER), 
            parse_url($this->url, PHP_URL_USER)
        )->credentials;
    }
    public function getHost() {
        return self::withHost(
            parse_url($this->url, PHP_URL_HOST)
        )->host;
    }
    public function getPort() {
        return self::withPort(
            parse_url($this->url, PHP_URL_PORT)
        )->port;
    }
    public function getPath() {
        return self::withPath(
            parse_url($this->url, PHP_URL_PATH)
        )->path;
    }
    public function getQuery() {
        return self::withQuery(
            parse_url($this->url, PHP_URL_QUERY)
        )->query;
    }
    public function getFragment() {
        return self::withFragment(
            parse_url($this->url, PHP_URL_FRAGMENT)
        )->fragment;
    }
    public function withScheme($scheme) {
//        if(Protocols::isAllowedProtocol($scheme)) {
            $this->scheme = $scheme;
        // } else {
        //     throw new \Exception('URI Scheme is not expected');
        // }
        // return $this;
    }
    public function withUserInfo($user, $password = null) {
        $user = trim($user);
        $password = trim($password);
        if(is_string($user) 
            && is_string($password)
            && $user != ''
            && $password != null
            && $password != '' ) {

            $this->credentials['user'] = $user;
            $this->credentials['password'] = $password;     

        } else {
            throw new \Exception('URI Invalid Username and Password');
        }
        return $this;
    }
    public function withHost($host) {
        if( is_string($host) && !empty($host)) {
            $this->host = $host;
        } else {
            throw new \Exception('Host is not identified');
        }
        return $this;
    }
    public function withPort($port) {
        if(is_numeric($port) && (int)$port > -1) {
            $this->port = $port;
        } else {
            throw new \Exception('Invalid Port using to communicate');
        }
        return $this;
    }
    public function withPath($path) {
        if(is_string($path) && $path != '') {
            $this->path = $path;
        } else {
            throw new \Exception('Request Path not found');
        }
        return $this;
    }
    public function withQuery($query) {
        if(is_string($query) && $query != '') {
            $this->query = $query;
        } else {
            throw new \Exception('Request Query not found');
        }
        return $this;
    }
    public function withFragment($fragment) {
        if(is_string($fragment) && $fragment != '') {
            $this->fragment = $fragment;
        } else {
            throw new \Exception('URI Fragment is not available');
        }
        return $this;
    }
    public function __toString() {
        return implode('',[
            ($this->scheme === "" ? $this->getScheme() : $this->scheme), 
            '://',
            $this->credentails(),
            ($this->host === '' ? $this->getHost() : $this->host),
            ':', $this->port,
            '/', ($this->path === '' ? $this->getPath() : $this->path),
            '?', ($this->query === '' ? $this->getQuery() : $this->query),
            '#', $this->fragment
        ]);
    }
    private function credentails() {
        if( !empty($this->credentails['username'])
            && !empty($this->credentails['password']) ) {
                return $this->credentails['username'].':'.$this->credentails['password'].'@';
        } 
        return '';
    }
}