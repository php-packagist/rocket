<?php

namespace PhpPackagist\Rocket\Exception;

class FailedException extends \Exception
{
    protected $response;

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}