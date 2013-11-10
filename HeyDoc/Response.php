<?php

namespace HeyDoc;

use Symfony\Component\HttpFoundation\Response as BaseResponse;

class Response extends BaseResponse
{
    /**
     * Factory method for chainability and send
     *
     * Example:
     *
     *     return Response::createAndSend($body, 200);
     *
     * @param string   $content  The response content
     * @param integer  $status   The response status code
     * @param array    $headers  An array of response headers
     */
    public static function createAndSend($body, $status = 200, array $headers = array())
    {
        $response = new static($body, $status, $headers);
        $response->send();
    }

    /**
     * Sets the response content.
     *
     * Valid types are strings, numbers, and objects that implement a __toString() method.
     *
     * @param mixed $content
     *
     * @return Response
     *
     * @throws \UnexpectedValueException
     *
     * @api
     */
    public function setContent($content)
    {
        parent::setContent($content);

        $this->setETag(md5($content));

        return $this;
    }
}
