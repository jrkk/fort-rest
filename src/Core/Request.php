<?php
namespace App\Core;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

class Request implements ServerRequestInterface
{

    protected $httpVersion = "";

    protected $RAW_BODY = "";

    protected $origins = [];
    protected $method = '';

    protected $header = null;
    protected $uri = null;
    protected $server = null;
    function __construct(Header &$header)
    {
        $this->header = $header;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function withServer($server)
    {
        $this->server = $server;
        return $this;
    }

    public function getProtocolVersion()
    {
        if ($this->httpVersion == '')
            $this->withProtocolVersion($_SERVER['SERVER_PROTOCOL']);
        return $this->httpVersion;
    }

    public function withProtocolVersion($version)
    {
        $this->httpVersion = $version;
        return $this;
    }

    public function getHeaders()
    {
        $headers = getallheaders();
        $this->header->parse($headers);
        return $this->header->getAll();
    }

    public function hasHeader($name)
    {
        try {
            return $this->header->has($name);
        } catch (NotFoundException $nfe) {
            return false;
        }
        return false;
    }

    public function getHeader($name)
    {
        try {
            return $this->header->get($name);
        } catch (ContainerException $nfe) {
            return false;
        }
        return false;
    }

    public function getHeaderLine($name)
    {
        try {
            return $name . ":" . $this->header->get($name);
        } catch (ContainerException $nfe) {
            return '';
        }
        return '';
    }

    public function withHeader($name, $value)
    {
        if (!is_string($name)
            || empty($name)
            || empty($value)) {
            return false;
        }
        $value = (string)$value;
        $this->header->set($name, $value);
        return $this;
    }

    public function withAddedHeader($name, $value)
    {
        try {
            $values = $this->header->get($name);
            if (is_array($value) && is_array($values)) {
                $values = array_merge($values, $value);
            } else {
                if (is_array($values))
                    $values = array_merge($values, [$value]);
                else
                    $values = array_merge([$values], [$value]);
            }
            $this->header->set($name, $values);
        } catch (ContainerException $nfe) {
            $this->header->set($name, $value);
        }
        return $this;
    }

    public function withoutHeader($name)
    {
        $this->header->remove($name);
    }

    public function getBody()
    {
        return $this->RAW_BODY;
    }

    public function withBody(StreamInterface $body)
    {
        $this->RAW_BODY = file_get_contents("php://input");
        return $this;
    }

    public function getRequestTarget()
    {
        return $this->origins;
    }

    public function withRequestTarget($requestTarget)
    {
        $this->origins = array_merge($this->origins, $requestTarget);
        return $this;
    }

    public function getMethod($upper = false)
    {
        if ($this->method != '') {
            return $this->method;
        }
        $method = $_SERVER['REQUEST_METHOD'];
        if (empty($method) || $method == '') {
            $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        }
        $method = $upper ? strtoupper($method) : strtolower($method);
        return $this->withMethod($method)->method;
    }

    public function withMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;
        return $this;
    }

    public function getServerParams()
    {

    }

    public function getCookieParams()
    {

    }

    public function withCookieParams(array $cookies)
    {

    }

    public function getQueryParams()
    {

    }

    public function withQueryParams(array $query)
    {

    }

    public function getUploadedFiles()
    {

    }

    public function withUploadedFiles(array $uploadedFiles)
    {

    }

    public function getParsedBody()
    {

    }

    public function withParsedBody($data)
    {

    }

    public function getAttributes()
    {

    }

    public function getAttribute($name, $default = null)
    {

    }

    public function withAttribute($name, $value)
    {

    }

    public function withoutAttribute($name)
    {

    }
}

