<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->setPhantomJs('/path/to/directory/that/contains/phantomjs/executable'); // Executable file in directory must be called 'phantomjs'