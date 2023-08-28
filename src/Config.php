<?php

namespace PhpPackagist\Rocket;

class Config
{
    /**
     * @var string Endpoint of the RPC server
     */
    public $endpoint;

    /**
     * @var string[] Default request options
     */
    public $options = array(
        "timeout" => 2,
    );

    /**
     * @var string[] Headers to be sent with the request
     */
    public $headers = array(
        'User-Agent' => 'rocket-client/1.0.0',
        "Content-Type" => 'application/json'
    );

    /**
     * @param $config
     * @return static
     */
    public static function create($config = array())
    {
        $instance = new static();

        if (isset($config['endpoint'])) {
            $instance->endpoint = rtrim($config['endpoint'], '/');
        }

        if (isset($config['options'])) {
            $instance->options = $config['options'];
        }

        if (isset($config['headers'])) {
            $instance->headers = $config['headers'];
        }

        return $instance;
    }
}