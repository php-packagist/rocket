<?php

namespace PhpPackagist\Rocket;

use Exception;

class RequestException extends Exception
{
    /**
     * The response instance.
     *
     * @var Response
     */
    public $response;

    /**
     * Create a new exception instance.
     *
     * @param Response $response
     *
     * @return void
     */
    public function __construct(Response $response)
    {
        list($code, $message) = $this->prepareResponse($response);

        parent::__construct($message, $code);

        $this->response = $response;
    }

    /**
     * Prepare the exception message.
     *
     * @param Response $response
     *
     * @return array
     */
    protected function prepareResponse(Response $response)
    {
        $errors = $response->json();

        if (is_array($errors) && isset($errors['message']) && isset($errors['code'])) {
            return array($errors['code'], $errors['message']);
        }

        return array($response->status(), $response->body());
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
