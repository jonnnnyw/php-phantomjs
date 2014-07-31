---
layout: post
title: Introduction
categories: []
tags: []
fullview: true
---

PHP PhantomJS is a flexible PHP library to load pages through the PhantomJS headless browser and return the page response. It is handy for testing websites that demand javascript support and also supports screen captures.

Feature List
------------

*  Load webpages through the PhantomJS headless browser
*  View detailed response data including page content, headers, status
   code etc.
*  Handle redirects
*  View javascript console errors
*  View detailed PhantomJS debuged information
*  Save screen captures to local disk
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

    "config": {
        "bin-dir": "bin"
    }
{% endhighlight %}

Finally, install PHP PhantomJS from the root of your project:

{% highlight bash %}
    
    #bash
    
    $ composer require "jonnyw/php-phantomjs:3.*"
{% endhighlight %}

If you would like to use another installation method or would like to see more detailed installation instructions, see the [installation]({{ site.BASE_PATH }}/installation.html) documentation.

Basic Usage
-----------

The following illustrates how to make a basic GET request and output the page content:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();

    /** 
     * @see JonnyW\PhantomJs\Message\Request 
     **/
    $request = $client->getMessageFactory()->createRequest('http://google.com', 'GET');

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
    
{% endhighlight %}

And if you would like to save a screen capture to local disk:

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();

    /** 
     * @see JonnyW\PhantomJs\Message\CaptureRequest
     **/
    $request = $client->getMessageFactory()->createCaptureRequest('http://google.com', 'GET');
    $request->setCaptureFile('/path/to/save/capture/file.jpg');

    /** 
     * @see JonnyW\PhantomJs\Message\Response 
     **/
    $response = $client->getMessageFactory()->createResponse();

    // Send the request
    $client->send($request, $response);
    
{% endhighlight %}

For more detailed examples see the [usage]({{ site.BASE_PATH }}/usage.html) section, or to create your own custom scripts check out the [advanced]({{ site.BASE_PATH }}/advanced.html) documentation.

