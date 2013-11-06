<?php

namespace HeyDoc;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest
{
    public function getPath()
    {
        return str_replace($this->getBaseUrl(), '', $this->getRequestUri());
    }
}
