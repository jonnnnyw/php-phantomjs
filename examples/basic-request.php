<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$request  = $client->getMessageFactory()->createRequest();
$response = $client->getMessageFactory()->createResponse();

$request->setMethod('GET');
$request->setUrl('http://google.com');

$client->send($request, $response);

var_dump($response);