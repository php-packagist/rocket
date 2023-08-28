<?php

namespace PhpPackagist\Rocket;

use PhpPackagist\Rocket\Exception\FailedException;

class Response
{
    /**
     * @var mixed
     */
    protected $response;

    /**
     * The decoded JSON response.
     *
     * @var array
     */
    protected $decoded;

    /**
     * @param \Guzzle\Http\Message\Response $response
     */
    public function __construct(\Guzzle\Http\Message\Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return int|string
     */
    public function status()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return bool
     */
    public function failed()
    {
        return $this->response->getStatusCode() != 200;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return $this->response->getStatusCode() == 200;
    }

    /**
     * @return array|bool|float|int|string
     */
    public function json()
    {
        if (! $this->decoded) {
            $this->decoded = $this->response->json();
        }

        return $this->decoded;
    }

    /**
     * @param $success
     * @param $failure
     * @return void
     */
    public function then($success, $failure = null)
    {
        if ($this->success()) {
             $success($this->json());
        } elseif ($failure) {
             $failure($this->json());
        }
    }

    /**
     * @param $callback
     * @return $this
     */
    public function tap($callback)
    {
        $callback($this->json());

        return $this;
    }

    /**
     * @throws FailedException
     */
    public function withThrow()
    {
        if ($this->failed()) {
            $e = new FailedException();
            $e->setResponse($this);
            throw $e;
        }

        return $this;
    }

    /**
     * @return \Guzzle\Http\EntityBodyInterface|string
     */
    public function __toString()
    {
        return $this->response->getBody(true);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->response, $name), $arguments);
    }
}