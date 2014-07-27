<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->debug(true); // Set debug flag

$request  = $client->getMessageFactory()->createRequest();
$response = $client->getMessageFactory()->createResponse();

$request->setMethod('GET');
$request->setUrl('http://google.com');

$client->send($request, $response);

// The PhantomJS executable log. Will contain 
// any script parse errors, script info and 
// anything else PhantomJS outputs in debug mode.
var_dump($client->getLog()); 