<?php
namespace App\Core;

use App\Exceptions\ContainerException;

class Response
{
    protected $headers = null;

    protected $_status_key = "status";
    protected $_error_key = "message";

    private $_sent_status = false;

    protected $data = [];
    function __construct(Header &$header)
    {
        $this->header = $header;
        $this->header->set('http_response_code', 501);
        $this->data[$this->_status_key] = "NO";
    }

    public function setStatus($code)
    {
        if ($code > 100 && $code < 510)
            $this->header->set('http_response_code', $code);
        return $this;
    }

    public function setSuccessState() {
        $this->data[$this->_status_key] = "OK";
        return $this;
    }

    public function setContentType($mime, $characterSet = 'utf-8') {
        $this->header->set('Content-Type', $mime.";".$characterSet);
        return $this;
    }

    public function setResponse($arg1, $arg2 = null)
    {
        if ($arg2 === null && is_array($arg1))
            $this->data = array_merge($this->data, $arg1);
        else
            $this->data[$arg1] = $arg2;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function send()
    {
        if($this->_sent_status === false) {
             $this->_sent_status = !$this->_sent_status;
        } else {
            return;  
        }
        http_response_code($this->header->get('http_response_code'));
        $headers = $this->header->getAll();
        foreach ($headers as $header => $value) {
            header($header . ':' . $value);
        }
        echo json_encode($this->data);
    }

    public function sendError($code, $message)
    {
        $this->header->set('http_response_code', $code);
        $this->data[$this->_status_key] = "NO";
        $this->data[$this->_error_key] = $message;
        return $this;
    }

}