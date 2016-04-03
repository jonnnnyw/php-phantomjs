---
layout: post
title: Introduction
categories: []
tags: []
fullview: true
version: 4.0
permalink: /4.0/
---

PHP PhantomJS is a flexible PHP library to load pages through the PhantomJS headless browser and return the page response. It is handy for testing websites that demand javascript support and also supports screen captures and PDF output.

Feature List
------------

*  Load webpages through the PhantomJS headless browser
*  View detailed response data including page content, headers, status
   code etc.
*  Handle redirects
*  View javascript console errors
*  View detailed PhantomJS debuged information
*  Save screen captures to local disk
*  Output web pages to PDF document
*  Set viewport size
*  Define screen capture x, y, width and height parameters
*  Delay page rendering for a specified time
*  Execute PhantomJS with command line options
*  Easily build and run custom PhantomJS scripts

Prerequisites
-------------

PHP PhantomJS requires PHP **5.3.0** or greater to run.

Installation
------------

It is recommended that you use Composer to install PHP PhantomJS. First, add the following to your project’s `composer.json` file:

{% highlight yaml %}
    
    #composer.json

    "scripts": {
        "post-install-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ],
        "post-update-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ]
    }
    
{% endhighlight %}

This will ensure the latest version of PhantomJS is installed for your system, in your bin folder. If you haven’t defined your bin folder in your composer.json, add the path:

{% highlight yaml %}
    
    #composer.json
    
    "config": {
        "bin-dir": "bin"
    }
{% endhighlight %}

Finally, install PHP PhantomJS from the root of your project:

{% highlight bash %}
    
    #bash
    
    $ composer require "jonnyw/php-phantomjs:4.*"
{% endhighlight %}

If you would like to use another installation method or would like to see more detailed installation instructions, see the [installation]({{ site.BASE_PATH }}/4.0/2-installation/) documentation.

> #### Important
> By default the PhantomJS library will look for the PhantomJS executable in the bin folder relative to where your script is running `~/bin/phantomjs`. If the executable cannot be found or if the path to your PhantomJS executable differs from the default location, for example you have installed PhantomJS globally, you will need to define the path to your PhantomJS executable manually.
> 
> `$client->getEngine()->setPath('/path/to/phantomjs');`

Basic Usage
-----------

The following illustrates how to make a basic GET request and output the page content:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();

    /** 
     * @see JonnyW\PhantomJs\Http\Request
     **/
    $request = $client->getMessageFactory()->createRequest('http://jonnyw.me', 'GET');

    /** 
     * @see JonnyW\PhantomJs\Http\Response 
     **/
    $response = $client->getMessageFactory()->createResponse();

    // Send the request
    $client->send($request, $response);

    if($response->getStatus() === 200) {

        // Dump the requested page content
        echo $response->getContent();
    }
    
{% endhighlight %}

Saving a screen capture to local disk:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();
    
    $width  = 800;
    $height = 600;
    $top    = 0;
    $left   = 0;
    
    /** 
     * @see JonnyW\PhantomJs\Http\CaptureRequest
     **/
    $request = $client->getMessageFactory()->createCaptureRequest('http://jonnyw.me', 'GET');
    $request->setOutputFile('/path/to/save/capture/file.jpg');
    $request->setViewportSize($width, $height);
    $request->setCaptureDimensions($width, $height, $top, $left);

    /** 
     * @see JonnyW\PhantomJs\Http\Response 
     **/
    $response = $client->getMessageFactory()->createResponse();

    // Send the request
    $client->send($request, $response);
    
{% endhighlight %}

Outputting a page as PDF:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();

    /** 
     * @see JonnyW\PhantomJs\Http\PdfRequest
     **/
    $request = $client->getMessageFactory()->createPdfRequest('http://jonnyw.me', 'GET');
    $request->setOutputFile('/path/to/save/pdf/document.pdf');
    $request->setFormat('A4');
    $request->setOrientation('landscape');
    $request->setMargin('1cm');

    /** 
     * @see JonnyW\PhantomJs\Http\Response 
     **/
    $response = $client->getMessageFactory()->createResponse();

    // Send the request
    $client->send($request, $response);
    
{% endhighlight %}

For more detailed examples see the [usage]({{ site.BASE_PATH }}/4.0/3-usage/) section, or you can [create your own custom scripts]({{ site.BASE_PATH }}/4.0/4-custom-scripts/).

