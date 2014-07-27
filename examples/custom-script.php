<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

// Create a file with a custom name 
// e.g. custom_procedure.proc and save it 
// somewhere. Set the parameters below to
// the name of your file and the location of
// your file. You can have many files in the 
// same location and you only need to create
// 1 procedure loader with the path to your
// files. The only restriction is the extension
// your files must be .proc

$fileName = 'custom_procedure';
$filePath = '/path/to/your/procedure/';

$serviceContainer = ServiceContainer::getInstance();

$procedureLoaderFactory = $serviceContainer->get('procedure_loader_factory');
$procedureLoader        = $procedureLoaderFactory->createProcedureLoader($filePath); // Set the path to your custom procedure(s)

$client = Client::getInstance();
$client->getProcedureLoader()->addLoader($procedureLoader); // Add new loader with path to you procedures to the chain loader

$request  = $client->getMessageFactory()->createRequest();
$response = $client->getMessageFactory()->createResponse();

$request->setType($fileName); // Set the request type to the name of your procedure you want to execute for this request

$client->send($request, $response);

var_dump($response);

// If your debug log contains 'SyntaxError: Parse error'
// then your custom procedure has a javascript error. Try 
// setting $client->debug(true) before making your request
// to get more information about your error.
var_dump($client->getLog());