PHP PhantomJS
=============

PHP PhantomJS is a simple PHP library to load pages through the PhantomJS 
headless browser and return the page response. It is handy for testing
websites that demand javascript support and also supports screen captures.


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
through PhantomJS then this is not for you. Check out [PhantomJS runner](https://github.com/Dachande663/PHP-PhantomJS).


2.0 Examples
------------

Request a URL and output the reponse:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createRequest('GET', 'http://google.com');

/** 
 * @see JonnyW\PhantomJs\Message\Response 
 **/
$response = $client->getMessageFactory()->createResponse();

// Send the request
$client->send($request, $response);

if($response->getStatus() === 200) {
	
	// Dump the requested page content
	echo $response->getContent();
}
```

Request a URL, save a screen capture to provided location and return the response:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createRequest('GET', 'http://google.com');

/** 
 * @see JonnyW\PhantomJs\Message\Response 
 **/
$response = $client->getMessageFactory()->createResponse();

// Send the request
$client->send($request, $response, '/path/to/save/screen/capture.png');
```

Send post request with data:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createRequest();

$request->setMethod('POST');
$request->setUrl('http://google.com');

$request->setRequestData(array(
	'name' 	=> 'Test',
	'email' => 'test@jonnyw.me'
));

/** 
 * @see JonnyW\PhantomJs\Message\Response 
 **/
$response = $client->getMessageFactory()->createResponse();

// Send the request
$client->send($request, $response);
```

Set a request header:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createRequest();

$request->addHeader('User-Agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X) AppleWebKit/534.34 (KHTML, like Gecko) PhantomJS/1.9.2 Safari/534.34');
```

Get a response header:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createRequest('GET', 'http://google.com');

/** 
 * @see JonnyW\PhantomJs\Message\Response 
 **/
$response = $client->getMessageFactory()->createResponse();

// Send the request
$client->send($request, $response, '/path/to/save/screen/capture.png');

echo $response->getHeader('Cache-Control');
```

Handle response redirect:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createRequest('GET', 'http://google.com');

/** 
 * @see JonnyW\PhantomJs\Message\Response 
 **/
$response = $client->getMessageFactory()->createResponse();

// Send the request
$client->send($request, $response);

if($response->isRedirect()) {
	echo $response->getRedirectUrl();
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

3.0 Changelog
------------

### V2.0.0

Version 2.0.0 changes the way requests are made. Requests now require you 
to inject a request and response instance into the send method. The examples 
above illustrate how to make requests in version 2.0.0 and will not work 
for older versions.

* Requests now require you to inject a request and response instance when sending.
* Added message factory that can also be injected into client when instantiated.
* Customers headers can be set in requests.
* Request method can be set. Supports: OPTIONS, GET, HEAD, POST, PUT, DELETE, PATCH.
* Request data can be set. Useful when making post requests.

4.0 Troubleshooting
------------

If you are using V1.0.0 then the examples above won't work for you. It is reccommend that you upgrade to the latest version.

Look at the response class (JonnyW\PhantomJs\Response) to see what data you have access to.

An explanation of the errors that are thrown by the client:

### CommandFailedException

The command sent to the PhantomJS executable failed. This should be very rare and is probably my fault if this happens (sorry).

### InvalidUrlException

The URL you are providing is an invalid format. It is very loose verification.

### InvalidMethodException

The request method you are providing is invalid.

### NoPhantomJsException

The PhantomJS executable cannot be found or it is not executable. Check the path and permissions.

### NotWriteableException

The screen capture location you provided or your /tmp folder are not writeable. The /tmp folder is used to temporarily write the scripts that PhantomJS executes. They are deleted after execution or on failure.