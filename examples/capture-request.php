<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$request  = $client->getMessageFactory()->createCaptureRequest();
$response = $client->getMessageFactory()->createResponse();

$top    = 10;
$left   = 10;
$width  = 200;
$height = 400;

$request->setMethod('GET');
$request->setUrl('http://google.com');
$request->setCaptureFile(sprintf('%s/file.jpg', sys_get_temp_dir()));
$request->setCaptureDimensions($width, $height, $top, $left);

$client->send($request, $response);

var_dump($response);

// If the capture dimensions were applied
// to the request, you will see an information
// notice in the debug log. This is useful for
// debugging captures and will always be present,
// even if debug mode is disabled.
var_dump($client->getLog());