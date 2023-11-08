<?php

namespace PhpPackagist\Rocket;

use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Exception\CurlException;

class Client
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var \Guzzle\Http\Client
     */
    protected $transporter;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->transporter = new \Guzzle\Http\Client(
            $config->endpoint
        );
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $params
     * @return Response
     * @throws RequestException
     * @throws CurlException
     */
    public function invoke($method, $path, $params = array(), $headers = array(), $options = array())
    {
        $request = $this->transporter->createRequest(
            $method, $path,
            array_merge_recursive($this->config->headers, $headers),
            $params,
            array_merge_recursive($this->config->options, $options)
        );

        try {
            $response = $request->send();
        } catch (RequestException $e) {
            $response = $e->getRequest()->getResponse();
            if (is_null($response)) {
                throw $e;
            }
        }

        return new Response($response);
    }
}
