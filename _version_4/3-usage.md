---
layout: post
title: Usage
categories: []
tags: []
fullview: true
version: 4.0
---

This page contains some common examples of how to use the PHP PhantomJS library.

* [Setup](#setup)
* [Basic Request](#basic-request)
* [POST Request](#post-request)
* [Other Request Methods](#other-request-methods)
* [Response Data](#response-data)
* [Screen Captures](#screen-captures)
* [Output To PDF](#output-to-pdf)
* [Set Viewport Size](#set-viewport-size)
* [Set Background Color](#set-background-color)
* [Custom Timeout](#custom-timeout)
* [Delay Page Render](#delay-page-render)
* [PhantomJS Options](#phantomjs-options)
* [Exceptions](#exceptions)

For more advanced customization or to load your own PhantomJS scripts, see the [custom scripts]({{ site.BASE_PATH }}/4.0/custom-scripts/) section.

---

Setup
-----

By default the PhantomJS library will look for the PhantomJS executable in the bin folder relative to where your script is running `~/bin/phantomjs`. If the executable cannot be found or if the path to your PhantomJS executable differs from the default location, for example you have installed PhantomJS globally, you will need to define the path to your PhantomJS executable manually.

{% highlight php %}
    
    <?php
    
    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    $client->getEngine()->setPath('/path/to/phantomjs');
    
{% endhighlight %}

> #### Note
> The path must include the name of the PhantomJS executable in it, not just a path to the directory containing the executable.

Basic Request
-------------

A basic GET request:

{% highlight php %}
    
    <?php
    
    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    
    $request  = $client->getMessageFactory()->createRequest();
    $response = $client->getMessageFactory()->createResponse();
    
    $request->setMethod('GET');
    $request->setUrl('http://jonnyw.me');
    
    $client->send($request, $response);
    
    if($response->getStatus() === 200) {
        echo $response->getContent();
    }
{% endhighlight %}

You can also set the URL, request method and timeout period when creating a new request instance through the message factory:

{% highlight php %}

    <?php
     
    ...
    
    $request = $client->getMessageFactory()->createRequest('http://jonnyw.me', 'GET', 5000);
    
    ...
    
{% endhighlight %}

POST Request
------------

A basic POST request:

{% highlight php %}

    <?php
    
    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    
    $request  = $client->getMessageFactory()->createRequest();
    $response = $client->getMessageFactory()->createResponse();
    
    $data = array(
        'param1' => 'Param 1',
        'param2' => 'Param 2'
    );
    
    $request->setMethod('POST');
    $request->setUrl('http://jonnyw.me');
    $request->setRequestData($data); // Set post data
    
    $client->send($request, $response);
{% endhighlight %}

Other Request Methods
---------------------

The PHP PhantomJS library supports the following request methods:

* OPTIONS
* GET
* HEAD
* POST
* PUT
* DELETE
* PATCH

The request method can be set when creating a new request instance through the message factory:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    
    $request = $client->getMessageFactory()->createRequest('http://jonnyw.me', 'PUT');
{% endhighlight %}

Or on the request instance itself:

{% highlight php %}

    <?php

    ...
    
    $request = $client->getMessageFactory()->createRequest();
    $request->setMethod('PATCH');
    
    ...
    
{% endhighlight %}

Response Data
-------------

A standard response object gives you access to the following interface:

| Accessor            | Description                                                                               |  Return Type   |
| :-----------------: | ----------------------------------------------------------------------------------------- | :------------: |
| getHeaders()        | Returns an array of all response headers.                                                 | Array          |
| getHeader(*header*) | Returns the value for a specific response header e.g. Content-Type.                       | Mixed          |
| getStatus()         | The response status code e.g. 200.                                                        | Integer        |
| getContent()        | The raw page content of the requested page.                                               | String         |
| getContentType()    | The content type of the requested page.                                                   | String         |
| getUrl()            | The URL of the requested page.                                                            | String         |
| getRedirectUrl()    | If the response was a redirect, this will return the redirect URL.                        | String         |
| isRedirect()        | Will return true if the response was a redirect or false otherwise.                       | Boolean        |
| getConsole()        | Returns an array of any javascript errors on the requested page along with a stack trace. | Array          |

> #### Note
> If the response ever contains a status code of 0, chances are the request failed. Check the request [debug log]({{ site.BASE_PATH }}/4.0/troubleshooting/#how-do-i-debug-a-request) for more detailed information about what may have gone wrong.

Screen Captures
---------------

You can save screen captures of a page to your local disk by creating a screen capture request and setting the path you wish to save the file to:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    
    $request  = $client->getMessageFactory()->createCaptureRequest('http://jonnyw.me');
    $response = $client->getMessageFactory()->createResponse();
    
    $file = '/path/to/save/your/screen/capture/file.jpg';
    
    $request->setOutputFile($file);
    
    $client->send($request, $response);
{% endhighlight %}

You will need to make sure the directory that you are saving the file to exists and is writable by your application.

You can also set the width, height, x and y axis for your screen capture:

{% highlight php %}

    <?php
    
    ...
    
    $top    = 10;
    $left   = 10;
    $width  = 200;
    $height = 400;
    
    $request->setCaptureDimensions($width, $height, $top, $left);
    
    ...
    
{% endhighlight %}

Output To PDF
-------------

You can output a page to PDF by creating a PDF request and setting the path you wish to save the document to.

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    
    $request  = $client->getMessageFactory()->createPdfRequest('http://jonnyw.me');
    $response = $client->getMessageFactory()->createResponse();
    
    $file = '/path/to/save/your/pdf/document.pdf';
    
    $request->setOutputFile($file);
    
    $client->send($request, $response);
{% endhighlight %}

You can set the paper size and margin of the PDF.

{% highlight php %}

    <?php

    ...
    
    $width  = '10cm';
    $height = '20cm';
    $margin = '2cm';
    
    $request->setPaperSize($width, $height);
    $request->setMargin($margin);
    
    ...
    
{% endhighlight %}

If you prefer, you can set a standard paper format such as A4 instead of paper size.

{% highlight php %}

    <?php

    ...
    
    $format = 'A4';
    
    $request->setFormat($format);
    
    ...
    
{% endhighlight %}

Along with the paper orientation.

{% highlight php %}

    <?php

    ...
    
    $orientation = 'landscape';
    
    $request->setOrientation($orientation);
    
    ...
    
{% endhighlight %}

Set Viewport Size
-----------------

You can easily set the viewport size for a request:

{% highlight php %}

    <?php

    ...
    
    $width  = 200;
    $height = 400;
    
    $request  = $client->getMessageFactory()->createRequest('http://jonnyw.me');
    $request->setViewportSize($width, $height);
    
    ...
    
{% endhighlight %}

Set Background Color
--------------------

You can easily force the background color of the requested page by setting  a `backgroundColor` body style:

{% highlight php %}

    <?php

    ...
    
    $request  = $client->getMessageFactory()->createRequest('http://jonnyw.me');
    $request->setBodyStyles(array(
        'backgroundColor' => '#ff0000'
    ));
    
    ...
    
{% endhighlight %}

The `setBackgroudColor` method can be used to set any valid CSS styles on the body tag; it is not limited to just background color.

Custom Timeout
--------------

By default, each request will timeout after 5 seconds. You can set a custom timeout period (in milliseconds) for each request:

{% highlight php %}

    <?php
    
    ...
    
    $timeout = 10000; // 10 seconds
    
    $request = $client->getMessageFactory()->createRequest('http://jonnyw.me');
    $request->setTimeout($timeout);
    
    ...
    
{% endhighlight %}

Delay Page Render
-----------------

Sometimes when saving a page to local disk you may want to wait until the page is completely loaded first. In this instance you can set a page render delay (in seconds) for the request:

{% highlight php %}

    <?php

    ...
    
    $delay = 5; // 5 seconds
    
    $request = $client->getMessageFactory()->createCaptureRequest('http://jonnyw.me');
    $request->setDelay($delay);
    
    ...
    
{% endhighlight %}


PhantomJS Options
-----------------

The PhantomJS API contains a range of command line options that can be passed when executing the PhantomJS executable. These can also be passed in via the client before a request:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    $client->getEngine()->addOption('--load-images=true');
    $client->getEngine()->addOption('--ignore-ssl-errors=true');
    
    $request  = $client->getMessageFactory()->createRequest('http://jonnyw.me');
    $response = $client->getMessageFactory()->createResponse();

    $client->send($request, $response);
{% endhighlight %}

You can also set a path to a JSON configuration file that contains multiple PhantomJS options:

{% highlight php %}

    <?php

    ...
    
    $client = Client::getInstance();
    $client->getEngine()->addOption('--config=/path/to/config.json');
    
    ...

{% endhighlight %}

See the [PhantomJS Documentation](http://phantomjs.org/api/command-line.html) for a full list of command line options.

Exceptions
----------

The following offers an explanation of the exceptions that may be raised by the PhantomJS library.

| Exception                  | Description                                                                                      |
| -------------------------- | ------------------------------------------------------------------------------------------------ |
| InvalidExecutableException | The path to the PhantomJS executable is invalid or is not executable.                            |
| InvalidMethodException     | The request method is invalid. It must be one of OPTIONS, GET, HEAD, POST, PUT, DELETE or PATCH. |
| InvalidUrlException        | The URL you are requesting is an invalid format.                                                 |
| NotExistsException         | A file could not be found or does not exist.                                                     |
| NotWritableException       | A file could not be written.                                                                     |
| ProcedureFailedException   | A PhantomJS script failed to execute successfully.                                               |
| RequirementException       | A PhantomJS script is missing a required element e.g. `phantom.exit(1);`.                        |
| SyntaxException            | A PhantomJS script contains a javascript syntax error.                                           |
