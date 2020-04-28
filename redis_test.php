<?php
require "vendor/autoload.php";

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => 'redis',
    'port'   => 6379,
]);

$key = 'orders:'. date("Ymd");

$orders = $client->get($key);

$columns = ['订单号','图片','规格','价钱','货币','数量','名称','店铺'];

//$columns = ['订单号','图片','规格','价钱'];

$spreadsheet->getActiveSheet()
    ->fromArray(
        $columns,  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
    //    we want to set these values (default is A1)
    );

