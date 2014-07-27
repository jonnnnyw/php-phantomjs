<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$request  = $client->getMessageFactory()->createCaptureRequest();
$response = $client->getMessageFactory()->createResponse();

$delay = 5; // Seconds

$request->setMethod('GET');
$request->setUrl('http://google.com');
$request->setCaptureFile(sprintf('%s/file.jpg', sys_get_temp_dir()));
$request->setDelay($delay);

$client->send($request, $response);

var_dump($response);

// A debug info notice will be written to
// the log when the page render delay starts 
// and when the page render executes. This is 
// useful for debugging page render delay and 
// will always be present, even if debug is disabled.
var_dump($client->getLog());