<?php

namespace PhpPackagist\Rocket\Exception;

use PhpPackagist\Rocket\Response;

/**
 * @mixin Response
 */
class FailedException extends \Exception
{
    /*
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @param $response
     * @return void
     */
    public function setResponse($response)
    {
        $this->response = $response;

        if ($this->response instanceof Response) {
            $this->setupResponse();
        }
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Setup the response object.
     *
     * @return void
     */
    protected function setupResponse()
    {
        $json = $this->response->json();

        if (!is_array($json)) {
            return ;
        }

        $this->code = $json['code'] ?: $this->code;
        $this->message = $json['message'] ?: $this->message;
        $this->reason = $json['reason'] ?: $this->reason;
    }

    /**
     * Call a method on the underlying response.
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if ($this->response instanceof Response && method_exists($this->response, $method)) {
            return call_user_func_array(array($this->response, $method), $args);
        }

        throw new \BadMethodCallException("Call to undefined method [{$method}]");
    }
}
