Usage
=====

This page contains some common examples of how to use the PHP PhantomJS
library.

-  `Basic Request <#basic-request>`__
-  `POST Request <#post-request>`__
-  `Other Request Methods <#other-request-methods>`__
-  `Response Data <#response-data>`__
-  `Screen Captures <#screen-captures>`__
-  `Set Viewport Size <#set-viewport-size>`__
-  `Custom Timeout <#custom-timeout>`__
-  `Delay Page Render <#delay-page-render>`__
-  `Custom Run Options <#custom-run-options>`__

Basic Request
-------------

A basic GET request:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();
        
        $request->setMethod('GET');
        $request->setUrl('http://google.com');
        
        $client->send($request, $response);
        
        if($response->getStatus() === 200) {
            echo $response->getContent();
        }

You can also set the URL, request method and timeout period when
creating a new request instance through the message factory:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com', 'GET', 5000);
        $response = $client->getMessageFactory()->createResponse();
            
        $client->send($request, $response);
        
        if($response->getStatus() === 200) {
            echo $response->getContent();
        }

POST Request
------------

A basic POST request:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();
        
        $data = array(
            'param1' => 'Param 1',
            'param2' => 'Param 2'
        );
        
        $request->setMethod('POST');
        $request->setUrl('http://google.com');
        $request->setRequestData($data); // Set post data
        
        $client->send($request, $response);

Other Request Methods
---------------------

The PHP PhantomJS library supports the following request methods:

-  OPTIONS
-  GET
-  HEAD
-  POST
-  PUT
-  DELETE
-  PATCH

The request method can be set when creating a new request instance
through the message factory:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com', 'PUT');

Or on the request instance itself:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest();
        $request->setMethod('PATCH');

Response Data
-------------

A standard response gives you access to the following interface:

+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| Accessor                | Description                                                                                 | Return Type   |
+=========================+=============================================================================================+===============+
| getHeaders()            | Returns an array of all response headers.                                                   | Array         |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getHeader(\ *header*)   | Returns the value for a specific response header e.g. Content-Type.                         | Mixed         |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getStatus()             | The response status code e.g. 200.                                                          | Int           |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getContent()            | The raw page content of the requested page.                                                 | String        |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getContentType()        | The content type of the requested page.                                                     | String        |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getUrl()                | The URL of the requested page.                                                              | String        |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getRedirectUrl()        | If the response was a redirect, this will return the redirect URL.                          | String        |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| isRedirect()            | Will return true if the response was a redirect or false otherwise.                         | Boolean       |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+
| getConsole()            | Returns an array of any javascript errors on the requested page along with a stack trace.   | Array         |
+-------------------------+---------------------------------------------------------------------------------------------+---------------+

If the response contains a status code of 0, chances are the request
failed. Check the request `debug
log <https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/troubleshooting.rst#how-do-i-debug-a-request>`__
for more detailed information about what may have gone wrong.

Screen Captures
---------------

You can save screen captures of a page to your local disk by creating a
screen capture request and setting the path you wish to save the file
to:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createCaptureRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
        
        $file = '/path/to/save/your/screen/capture/file.jpg';
        
        $request->setCaptureFile($file);
        
        $client->send($request, $response);

You will need to make sure the directory that you are saving the file to
exists and is writable by your application.

You can also set the width, height, x and y axis for your screen
capture:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createCaptureRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
        
        $file = '/path/to/save/your/screen/capture/file.jpg';
        
        $top    = 10;
        $left   = 10;
        $width  = 200;
        $height = 400;
        
        $request->setCaptureFile($file);
        $request->setCaptureDimensions($width, $height, $top, $left);
        
        $client->send($request, $response);

Set Viewport Size
-----------------

You can easily set the viewport size for a request:

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
            
        $width  = 200;
        $height = 400;
        
        $request->setViewportSize($width, $height);
        
        $client->send($request, $response);

Custom Timeout
--------------

By default, each request will timeout after 5 seconds. You can set a
custom timeout period (in milliseconds) for each request:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
        
        $timeout = 10000; // 10 seconds
        
        $request->setTimeout($timeout);
        
        $client->send($request, $response);

Delay Page Render
-----------------

Sometimes when taking screen captures you may want to wait until the
page is completely loaded before saving the capture. In this instance
you can set a page render delay (in seconds) for the request:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $request  = $client->getMessageFactory()->createCaptureRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
        
        $delay = 5; // 5 seconds
        
        $request->setDelay($delay);
        
        $client->send($request, $response);

You can set a page render delay for standard requests also.

Custom Run Options
------------------

The PhantomJS API contains a range of command line options that can be
passed when executing the PhantomJS executable. These can also be passed
in via the client before a request:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        $client->addOption('--load-images=true');
        $client->addOption('--ignore-ssl-errors=true');
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

You can also set a path to a JSON configuration file that contains
multiple PhantomJS options:

.. code:: php


        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        $client->addOption('--config=/path/to/config.json');
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

See the `PhantomJS
Documentation <http://phantomjs.org/api/command-line.html>`__ for a full
list of command line options.