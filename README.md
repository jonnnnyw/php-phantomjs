PHP PhantomJS
=============

PHP PhantomJS is a simple PHP library to load pages through the PhantomJS 
headless browser and return the page response.


0.0 Table of Contents
---------------------

* Introduction
* Examples
* Changelog
* Troubleshooting


1.0 Introduction
----------------

This library provides the ability to load pages through the PhantomJS 
headless browser and return the page response. It also lets you screen
capture pages and save the captures to disk. It is designed to meet a 
simple objective and does not offer a PHP API to the full suite of 
PhantomJS functionality.

The PhantomJS executable comes bundled with the library. If installed
via composer, the file will be symlinked in your bin folder.

If you are looking for a PHP library to run external javascript files 
through PhantomJS then this is not for you. Check out  
[PhantomJS runner](https://github.com/Dachande663/PHP-PhantomJS).


2.0 Examples
------------

Load a URL and return the response:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Response 
 **/
$response = $client->open('http://www.google.com');

if($response->getStatus() === 200) {
	
	// Dump the response content
	echo $response->getContent();
}

```

Load a URL, save a screen capture to provided location and return the response:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Response 
 **/
$response = $client->capture('http://www.google.com', '/path/to/save/screen/capture.png');
```

Handle response redirect:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Response 
 **/
$response = $client->open('http://www.google.com');

if($response->isRedirect()) {

	$response = $client->open(
		$response->getRedirectUrl()
	);
}
```

Define a new path to the PhantomJS executable

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->setPhantomJs('/path/to/phantomjs');

/** 
 * @see JonnyW\PhantomJs\Response 
 **/
$response = $client->open('http://www.google.com');

if($response->isRedirect()) {

	$response = $client->open(
		$response->getRedirectUrl()
	);
}
```

Set timeout for the request (defaults to 5 seconds):

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->setTimeout(10000); // In milleseconds

/** 
 * @see JonnyW\PhantomJs\Response 
 **/
$response = $client->open('http://www.google.com');

if($response->isRedirect()) {

	$response = $client->open(
		$response->getRedirectUrl()
	);
}
```

3.0 Troubleshooting
------------