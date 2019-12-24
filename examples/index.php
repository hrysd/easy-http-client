<?php

include_once __DIR__ . '/../vendor/autoload.php';

use EasyHttpClient\Client;

$client = new Client('https://hrysd.org');

var_dump($client->get('/'));