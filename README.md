PHP PhantomJS
=============

PHP PhantomJS is a simple PHP library to load pages through the PhantomJS 
headless browser and return the page response. It is handy for testing
websites that demand javascript support and also supports screen captures.


0.0 Table of Contents
---------------------

* Introduction
* Examples
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
through PhantomJS then this is not for you. Check out [PhantomJS runner](https://github.com/Dachande663/PHP-PhantomJS).


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
	
	// Dump the requested page content
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

Get a response header:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Response 
 **/
$response = $client->open('http://www.google.com');

echo $response->getHeader('Cache-Control');
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

Define a new path to the PhantomJS executable:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->setPhantomJs('/path/to/phantomjs');
```

Set timeout for the request (defaults to 5 seconds):

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();
$client->setTimeout(10000); // In milleseconds
```

3.0 Troubleshooting
------------

Look at the response class (JonnyW\PhantomJs\Response) to see what data you have access to.

An explanation of the errors that are thrown by the client:

### CommandFailedException

The command sent to the PhantomJS executable failed. This should be very rare and is probably my fault if this happens (sorry).

### InvalidUrlException

The URL you are providing is an invalid format. It is very loose verification.

### NoPhantomJsException

The PhantomJS executable cannot be found or it is not executable. Check the path and permissions.

### NotWriteableException

The screen capture location you provided or your /tmp folder are not writeable. The /tmp folder is used to temporarily write the scripts that PhantomJS executes. They are deleted after execution or on failure.