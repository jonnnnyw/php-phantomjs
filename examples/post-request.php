<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$request  = $client->getMessageFactory()->createRequest();
$response = $client->getMessageFactory()->createResponse();

$data = array(
    'param1' => 'Param 1',
    'param2' => 'Param 2'
);

$request->setMethod('POST');
$request->setUrl('http://google.com');
$request->setRequestData($data); // Set post data

$client->send($request, $response);

var_dump($response);