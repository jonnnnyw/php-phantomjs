Introduction
============

| PHP PhantomJS is a flexible PHP library to load pages through the
PhantomJS
| headless browser and return the page response. It is handy for testing
| websites that demand javascript support and also supports screen
captures.

Feature List
------------

-  Load webpages through the PhantomJS headless browser
-  View detailed response data including page content, headers, status
   code etc.
-  Handle redirects
-  View javascript console errors
-  View detailed PhantomJS debuged information
-  Save screen captures to local disk
-  Define screen capture x, y, width and height parameters
-  Set viewport size
-  Delay page rendering for a specified time
-  Execute PhantomJS with command line options
-  Easily build and run custom PhantomJS scripts

Prerequisites
-------------

PHP PhantomJS requires PHP **5.3.0** or greater to run.

Installation
------------

It is recommended that you use Composer to install PHP PhantomJS. First,
add the following to your project’s ``composer.json`` file:

.. code:: xml

    "scripts": {
        "post-install-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ],
        "post-update-cmd": [
            "PhantomInstaller\\Installer::installPhantomJS"
        ]
    }

This will ensure the latest version of PhantomJS is installed for your
system, in your bin folder. If you haven’t defined your bin folder in
your composer.json, add the path:

.. code:: xml

    "config": {
        "bin-dir": "bin"
    }

Finally, install PHP PhantomJS from the root of your project:

.. code:: shell

    $ composer require "jonnyw/php-phantomjs:3.*"

If you would like to use another installation method or would like to
see more detailed installation instructions, see the `installation <https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/installation.rst>`__
documentation.

Basic Usage
-----------

The following illustrates how to make a basic GET request and output the
page content:

.. code:: php

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

And if you would like to save a screen capture to local disk:

.. code:: php

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

For more detailed examples see the `usage`_ section, or to create
your own custom scripts check out the `advanced`_ documentation.

.. _usage: https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/usage.rst
.. _advanced: https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/advanced.rst
