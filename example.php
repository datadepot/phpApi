<?php
/*
DataDepot.io php API
GitHub repo:
https://github.com/datadepot/phpApi

Set event example:
*/
define('DD_API_ID', '...');
define('DD_API_KEY', '...');
define('DD_SITE_ID', '...');
define('DD_EVENT_ID', '...');

include_once('Datadepot.class.php');
try {
    $dd = new Datadepot(DD_API_ID, DD_API_KEY, DD_SITE_ID);

    //get deviceID from browser cookies
    $device_id = $dd->getUid();

    //post event
    $dd->postEvent(DD_EVENT_ID, $device_id, [
        'order_id'=>'1047',
        'items' => [
            [
            'id' => 'pp-1261',
            'name' => 'Pear',
            'price' => 1.25,
            'count' => 10,
            'total' => 12.5
            ],
            [
            'id' => 'pp-1000',
            'name' => 'Apple',
            'price' => 0.99,
            'count' => 5,
            'total' => 4.95
            ],
        ],
        'add_date' => '2016-06-10 12:43',
        'customer_id'=> 101,
        'order_total'=>17.45    
    ]);
} catch (Exception $exception) {
    /*
    //show errors
    if (!empty($exception->getMessage())) {
        var_dump($exception->getMessage()); 
    }
    */
}