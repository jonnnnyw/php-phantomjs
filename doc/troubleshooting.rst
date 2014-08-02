Troubleshooting
===============

-  `It's not installing anything to my bin
   directory <#its-not-installing-anything-to-my-bin-directory>`__
-  `I am getting a InvalidExecutableException when making a
   request <#i-am-getting-a-invalidexecutableexception-when-making-a-request>`__
-  `I am getting a NotWritableException when making a
   request <#i-am-getting-a-notwritableexception-when-making-a-request>`__
-  `Why do I need the phantomloader
   file? <#why-do-i-need-the-phantomloader-file>`__
-  `Why am I getting a status code of 0 in the
   response? <#why-am-i-getting-a-status-code-of-0-in-the-response>`__
-  `It's not saving my screenshots <#its-not-saving-my-screenshots>`__
-  `Can I set the screenshot size? <#can-i-set-the-screenshot-size>`__
-  `Can I set the viewport size? <#can-i-set-the-viewport-size>`__
-  `How do I debug a request? <#how-do-i-debug-a-request>`__
-  `I am getting SyntaxError: Parse error in the debug
   log <#i-am-getting-syntaxerror-parse-error-in-the-debug-log>`__

It's not installing anything to my bin directory
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

When installing via composer, as outlined in the `installation
guide <https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/installation.rst>`__, it is recommended
that you define the location of the bin folder for your project. This
can be done by adding the following to your ``composer.json`` file:

.. code:: yaml

        #composer.json
    
        {
            "config": {
                "bin-dir": "/path/to/your/projects/bin/dir"
            }
        }

You need to make sure that this directory exists and is writable before
you install your composer depedencies.

Once you have defined your bin folder in your ``composer.json`` file,
run composer install from the root of your project:

.. code:: shell

        #bash
    
        $ php composer.phar install

This should install 2 files to your bin folder: ``phantomjs`` and
``phantomloader``. If you do not have these 2 files in your bin folder
then check that the folder is writable and run composer install again.

.. important::
    If you do not define a bin directory in your ``composer.json`` file
    then the default install location will be ``vendor/bin/``. If you
    choose to not set a bin folder path then you will need to make sure
    that this path is set in the client by calling
    ``$client->setBinDir('vendor/bin');`` before making a request.

I am getting a ``InvalidExecutableException`` when making a request
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you have installed via composer, as outlined in the `installation
guide <https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/installation.rst>`__, then you should
have 2 files installed in either the ``bin`` or ``vendor/bin/``
directory, in the root of your project. These files are called
``phantomjs`` and ``phantomloader``.

Check that these files exist and are executable by your application. If
they do not exist, see `It's not installing anything to my bin
directory <#its-not-installing-anything-to-my-bin-directory>`__.

If the PHP PhantomJS library cannot locate either of these files then it
will throw a ``InvalidExecutableException``. The message in the
exception should tell you which one it can't execute. If both of these
files exist and they are executable by your application, you may need to
set the path to the directory that these files live in before making a
request:

.. code:: php

        <?php 
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
        $client->setBinDir('/path/to/bin/dir');

I am getting a ``NotWritableException`` when making a request
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

When making a request, the PHP PhantomJS library compiles a temporary
script file to your system's tmp folder. The location of this folder is
determined through the use of the ``sys_get_temp_dir()`` function. If
this directory is not writable by your application then you will receive
this exception.

Why do I need the ``phantomloader`` file?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A proxy loader file is used get around a quirk that PhantomJS has when
it encounters a syntax error in a script file. By default, if PhantomJS
encounters a syntax error when loading a script file it will not exit
execution. This means that PHP PhantomJS will continue to wait for a
response from PhantomJS until PHP reaches its script execution timeout
period. This also means you won't get any debug information informing
you that the PhantomJS script has a syntax error.

To get around this, script files are loaded through a loader script.
This handles the event of a syntax error and ensures that PhantomJS
exits when an error is encountered.

The ``phantomloader`` file is required in order for the PHP PhantomJS
library to run so please make sure that it was installed to your bin
folder and is readable by your application.

Another reason for getting this exception is when you are trying to save
screenshots. See `It's not saving my
screenshots <#its-not-saving-my-screenshots>`__.

Why am I getting a status code of 0 in the response?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A status code of 0 in the response object generally means the request
did not complete successfully. This could mean that the URL you are
requesting did not return a response or that something happened when
making the request that did not raise an error in the PHP PhantomJS
library.

Becuase and exception was not thrown, chances are the issue is with
PhantomJS itself or at the endpoint you are calling.

One possible reason for this is that your request has timed out before a
response was returned from the endpoint that you are requesting.
Depending on what you are requesting, some websites seem to take a while
to return a response. An example of this is
`myspace.com <https://myspace.com/>`__ which, at the time of writing
this, takes a considerable amount of time resolve through PhantomJS.

To work around this you can try increasing the timeout period in the PHP
PhantomJS client:

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
    
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
    
        $timeout = 20000; // 20 seconds
    
        $request->setTimeout($timeout);
    
        $client->send($request, $response);

If you are still having a problem then you should enable debugging,
before you make the request, and check the debug log. This contains a
dump of information from PhantomJS which could help to track down why
you are not getting a response.

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
        $client->debug(true); // Set debug flag
    
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
            
        $client->send($request, $response);
    
        echo $client->getLog(); // Output log

You can also try running a test script through your PhantomJS
executable, from the command line, to see if you get a valid response
back. Save the following script somewhere:

.. code:: javascript

        //test-script.js
    
        var page = require('webpage').create(),
            url = 'YOUR REQUEST URL', // Change this to the URL you want to request
            response; 
    
        page.onResourceReceived = function (r) {
            response = r;
        };
    
        phantom.onError = function(msg, trace) {
    
            console.log(msg);
            console.log(trace);
            phantom.exit(1);
        };
    
        page.open (url, 'GET', '', function (status) {
            
            console.log(status);
            console.log(JSON.stringify(response));
            phantom.exit();
        });

And then, assuming you have saved the script above to ``test-script.js``
in the root of your project and your PhantomJS executable is located at
``bin/phantomjs``, run the following:

.. code:: shell

        #bash
    
        $ bin/phantomjs ./test-script.js

You should see an output of the response from PhantomJS:

.. code:: shell

        #bash
    
        success
        {"contentType":"text/javascript; charset=UTF-8", "headers": ...
    

If you don't see ``success`` followed by a JSON encoded response object
then there is something the with the URL you are requesting or your
PhantomJS executable. Try reinstalling PhantomJS. If you see ``fail``
instead of ``success``, chances are the URL you are requesting is
invalid or not resolving.

It's not saving my screenshots
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

When making a capture request you need to make sure that you are setting
the file location that you want the screenshot saved to, including the
filename:

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
    
        $request  = $client->getMessageFactory()->createCaptureRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
    
        $file = '/path/to/save/your/screen/capture/file.jpg';
    
        $request->setCaptureFile($file); // Set the capture file
    
        $client->send($request, $response);

The file itself does not need to exist but the parent directory must
exist and be writable by your application. Check that your application
has permissions to write files to the directory you are setting for your
screen captures.

Can I set the screenshot size?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, you can set the width and height of your capture along with the x
and y coordinates of where the capture should start from:

.. code:: php

        <?php
    
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

Can I set the viewport size?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, you can set the viewport dimensions on both regular and capture
requests:

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
    
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
            
        $width  = 200;
        $height = 400;
    
        $request->setViewportSize($width, $height); // Set viewport size
    
        $client->send($request, $response);

How do I debug a request?
~~~~~~~~~~~~~~~~~~~~~~~~~

By setting the debug flag to ``true`` on the client, you can get a dump
of information output from PhantomJS along with some info events added
by the PHP PhantomJS library:

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
        $client->debug(true); // Set debug flag
    
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
            
        $client->send($request, $response);
    
        echo $client->getLog(); // Output log

You can also get any javacript console errors along with a stack trace
from the URL you are calling, in the response object:

.. code:: php

        <?php
    
        use JonnyW\PhantomJs\Client;
    
        $client = Client::getInstance();
    
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();
            
        $client->send($request, $response);
    
        var_dump($response->getConsole()); // Outputs array of console errors and stack trace

I am getting ``SyntaxError: Parse error`` in the debug log
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You will only get this error if the script file that is being run by
PhantomJS has a syntax error. If you are writing your own `custom
scripts <https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/advanced.rst#custom-phantomjs-scripts>`__
then try setting the `debug flag <#how-do-i-debug-a-request>`__ which
*should* print some more detailed information in the debug log. Also
check that you aren't setting any parameters to ``null`` in your request
object as this could be causing a javascript error due to javascript
variables being set to nothing e.g. ``var width = ,``.