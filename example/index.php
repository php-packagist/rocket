<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Rpc.php';

$rpc = new Rpc();

//try {
//    print_r($rpc->hello(1)->withThrow()->json());
//} catch (\PhpPackagist\Rocket\Exception\FailedException $e) {
//    var_dump($e->getResponse()->json());
//}

try {
    print_r($rpc->getToken('facebook', '9faf3c1e-7c94-4573-a248-62e149dcbe87', 'AQBrdgmJVTFT7BuDEcHPFDycezsf_QTN_IuMI2eQiOmWWYo_LveRYa1tgYbWkugZYDGvMUlhScgP7tWariNRTMfkd4Iec9fLBASnIJF-EOwkoxC4HYQV7hhlN8fU0smmit4sy2CRHz5nUk9DzVC6qS6w-wE-NLS9zJnPV2BVg8C2W0j5BdgQ-Siz6fXEV9chJX5QDjEked-9cRkwXK5Gv497SKX8TocI4HoPvW9WHf3oK-SVEDZ1MLql_OkYzuAEHo8-SSx1WrDkZ6Db19GvOh6GnhbPsoGmSKm2IJF5pHI2ptLzKsKRJsTijhw-tgJWV5q605_A9EZ00fpX1jDGSQjXy2IgHGybIvElZCVB1ucrrzMkFoAD7FCtq1UtbqgObxM')->withThrow()->json());
} catch (\PhpPackagist\Rocket\Exception\FailedException $e) {
    print_r($e->json());
    print_r($e->getCode().PHP_EOL);
    print_r($e->getMessage().PHP_EOL);
    print_r($e->getReason().PHP_EOL);
}
