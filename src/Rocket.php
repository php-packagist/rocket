<?php

namespace PhpPackagist\Rocket;

use InvalidArgumentException;

/**
 * Rocket RPC Client
 *
 * @mixin Request
 */
class Rocket
{
    /**
     * 连接示例
     *
     * @var array
     */
    protected $requests = array();

    /**
     * @var array
     */
    protected $config = array();

    /**
     * create a new rocket instance
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
    }

    /**
     * get request instance
     *
     * @param string $name
     *
     * @return Request
     */
    public function request($name = null)
    {
        $name = ! is_null($name) ? $name : $this->getDefaultRequest();

        if (! isset($this->requests[$name])) {
            $this->requests[$name] = $this->resolve($name);
        }

        return $this->requests[$name];
    }

    /**
     * return default request name
     *
     * @return string
     */
    protected function getDefaultRequest()
    {
        return $this->config['default'];
    }

    /**
     * resolve request instance by name
     *
     * @param string $name
     *
     * @return Request
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        return $this->create($config);
    }

    /**
     * get config by name
     *
     * @param string $name
     *
     * @return array
     */
    protected function getConfig($name)
    {
        $servers = $this->config['servers'];

        if (! isset($servers[$name])) {
            throw new InvalidArgumentException("Server [{$name}] not configured.");
        }

        return $servers[$name];
    }

    /**
     * create request instance
     *
     * @return Request
     */
    public static function create($config = array())
    {
        $request = new Request();

        if (! empty($config['base_url'])) {
            $request->baseUrl($config['base_url']);
        }

        if (! empty($config['timeout'])) {
            $request->timeout($config['timeout']);
        }

        return $request;
    }

    /**
     * magic call
     *
     * @param string $method
     * @param mixed  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $callable = array(self::create(), $method);

        return call_user_func_array($callable, $parameters);
    }
}
