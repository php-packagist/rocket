<?php

require __DIR__ . '/../vendor/autoload.php';

$rocket = new \PhpPackagist\Rocket\Rocket(array(
    'default' => 'default',
    'servers' => array(
        'default' => array('base_url' => 'http://192.168.2.8:8000/')
    ),
));

//try {
//    print_r($rocket->request('default')->get('/')->json());
//} catch (\PhpPackagist\Rocket\RequestException $e) {
//    print_r($e->getMessage());
//}

try {
    print_r($rocket->request('default')->get('/v1/oauth/facebook/is-bind', array(
        'open_id' => '251265190466790',
    ))->json());
} catch (\PhpPackagist\Rocket\RequestException $e) {
    print_r($e->getMessage());
}
