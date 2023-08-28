<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Rpc.php';

$rpc = new Rpc();

try {
    print_r($rpc->hello(1)->withThrow()->json());
} catch (\PhpPackagist\Rocket\Exception\FailedException $e) {
    var_dump($e->getResponse()->json());
}