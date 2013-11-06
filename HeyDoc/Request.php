<?php

namespace HeyDoc;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

class Request extends BaseRequest
{
    /**
     * Get path without base url
     *
     * @return string
     */
    public function getPath()
    {
        return str_replace($this->getBaseUrl(), '', $this->getRequestUri());
    }
}
