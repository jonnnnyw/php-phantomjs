<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$request  = $client->getMessageFactory()->createRequest();
$response = $client->getMessageFactory()->createResponse();

$request->setMethod('GET');
$request->setUrl('http://google.com');

$client->send($request, $response);

// Any javascript errors that show up in 
// the browser console will appear in 
// response console data along with stack 
// trace. console.log() data will not be present.
var_dump($response->getConsole()); 