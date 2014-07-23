Introduction
=============

PHP PhantomJS is a flexible PHP library to load pages through the PhantomJS 
headless browser and return the page response. It is handy for testing
websites that demand javascript support and also supports screen captures.

Feature List
---------------------

* Load webpages through the PhantomJS headless browser
* View detailed response data including page content, headers, status code etc.
* Handle redirects
* View javascript console errors
* View detailed PhantomJS debuged information
* Save screen captures to local disk 
* Define screen capture x, y, width and height parameters
* Delay page rendering for a specified time
* Execute PhantomJS with command line options
* Easily build and run custom PhantomJS scripts

Prerequisites
---------------------

PHP PhantomJS requires PHP **5.3.0** or greater to run.

Installation
---------------------

It is recommended that you use Composer to install PHP PhantomJS:

```xml
composer require "jonnyw/php-phantomjs:3.*"
```

If you would like to use another installation method or would like to see more detailed installation instruction, see the [installation](http://jonnnnyw.github.io/php-phantomjs/installation.html) documentation.


Basic Usage
---------------------

The following illustrates how to make a basic GET request and output the page content:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()
    ->createRequest('http://google.com', 'GET');

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

And if you would like to save a screen capture to local disk:

```php
<?php

use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

/** 
 * @see JonnyW\PhantomJs\Message\Request 
 **/
$request = $client->getMessageFactory()->createCaptureRequest('http://google.com', 'GET');
$request->setCaptureFile('/path/to/save/capture/file.jpg');

/** 
 * @see JonnyW\PhantomJs\Message\Response 
 **/
$response = $client->getMessageFactory()->createResponse();

// Send the request
$client->send($request, $response);


```

For more detailed examples see the [examples](http://jonnnnyw.github.io/php-phantomjs/examples.html) section, or to create your own custom scripts check out the [advanced](http://jonnnnyw.github.io/php-phantomjs/advanced.html) documentation.
