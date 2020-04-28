<?php
require "C:/ShopeeAdmin/ShopeeAdmin/vendor/autoload.php";

$rd = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

$rd->set('clock', true);
$rd->expire('clock', 600);

$client = new \GuzzleHttp\Client();

$cates = ['Toys', 'Luxury', 'Shoes', 'Sex', 'Electronic'];

foreach ($cates as $cat){
    $uri = 'http://127.0.0.1/order/toship?type=' . $cat;
    $response = $client->get($uri);
    echo $response->getBody();
	echo "\n";
    sleep(10);
}

$client->get('http://127.0.0.1/order/excel');

