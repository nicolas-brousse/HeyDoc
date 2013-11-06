<?php

namespace HeyDoc;

class Response
{
    protected $body;
    protected $statusCode;
    protected $headers;

    public function __construct($body = '', $statusCode = 200, array $headers = array())
    {
        $this->body       = $body;
        $this->headers    = new \ArrayObject($headers);

        $this->setStatusCode($statusCode);
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function addHeader($key, $val)
    {
        $this->headers->offsetSet($key, $val);
    }

    public function setStatusCode($statusCode)
    {

    }

    public static function createAndSend($body, $statusCode = 200, array $headers = array())
    {
        $response = new static($body, $statusCode, $headers);
        $response->send();
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    protected function sendHeaders()
    {
        foreach ($this->headers as $k=>$v) {
            header(sprintf('%s: %s', $k, $v));
        }
    }

    protected function sendContent()
    {
        $output = fopen('php://output', 'w');
        fwrite($output, $this->body);
        fclose($output);
    }
}
