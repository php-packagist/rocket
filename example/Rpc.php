<?php

use PhpPackagist\Rocket\Client;
use PhpPackagist\Rocket\Config;

class Rpc
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(Config::create(array(
            'endpoint' => 'http://192.168.2.184:8000',
            'timeout' => 2,
        )));
    }

    /**
     * @param $id
     * @return \PhpPackagist\Rocket\Response
     */
    public function hello($id)
    {
        return $this->client->invoke('GET', '/game/detail/2' . $id);
    }

    /**
     * @param $username
     * @param $password
     * @return \PhpPackagist\Rocket\Response
     */
    public function ldap($username, $password)
    {
        return $this->client->invoke('GET', '/ldap', array(
            'username' => $username,
            'password' => $password
        ));
    }
}