Advanced
========

-  `PhantomJS command line options <#phantomjs-command-line-options>`__
-  `Custom PhantomJS scripts <#custom-phantom-js-scripts>`__
-  `Writing a custom script <#writing-a-custom-script>`__
-  `Using custom request parameters in your
   script <#using-custom-request-parameters-in-your-script>`__
-  `Loading your script <#loading-your-script>`__

PhantomJS command line options
------------------------------

The PhantomJS API contains a range of command line options that can be
passed when executing the PhantomJS executable. These can also be passed
in via the client before a request:

.. code:: php


        <?php

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


        <?php

        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        $client->addOption('--config=/path/to/config.json');
        
        $request  = $client->getMessageFactory()->createRequest('http://google.com');
        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

See the `PhantomJS
Documentation <http://phantomjs.org/api/command-line.html>`__ for a full
list of command line options.

Custom PhantomJS scripts
------------------------

In most instances you shouldn't need to worry about the javascript files
that run the PHP PhantomJS library but there may be times when you want
to execute your own custom PhantomJS scripts through the client. This
can be easily achieved by using the built in script loader.

Script files or 'procedures' as they are referred to in the application
are closely mapped to requests. When you create a default request
instance, you are essentially running the default javascript procedure
that comes bundled with the application. When you create a capture
request you are running the capture procedure.

.. code:: php


        <?php

        use JonnyW\PhantomJs\Client;
        
        $client->getMessageFactory()->createRequest(); // ~/Resources/procedures/default.proc
        $client->getMessageFactory()->createCaptureRequest(); // ~/Resources/procedures/capture.proc

Writing a custom script
~~~~~~~~~~~~~~~~~~~~~~~

The first step in creating your script is to create a procedure file
somewhere. For the purpose of this guide we will refer to it as
``my_procedure.proc`` but in reality it can be called anything you like.
The only requirement is that the file extension must be ``.proc``.

Create the file somewhere and make sure it can be read by your
application. Make a note of the path to the directory where your file is
created as you will need this when loading your script which is
explained later in this guide.

.. code:: shell

        
        $ touch my_procedure.proc
        $ chmod 755 my_procedure.proc
        

Next open your procedure file in your text editor and write your
PhantomJS script. The `PhantomJS
documentation <http://phantomjs.org/quick-start.html>`__ has more
detailed information on writing custom scripts.

.. code:: javascript

        
        // my_procedure.proc

        var page  = require('webpage').create();
        
        page.open ('{{ request.getUrl() }}', '{{ request.getMethod() }}', '{{ request.getBody() }}', function (status) {
             
            // It is important that you exit PhantomJS
            // when your script has run or when you
            // encounter an error
            phantom.exit(1);
        });
        
        ...
        

.. important::
   Make sure that ``phantom.exit(1);`` is always called after your script has run or if you encounter an error. This requires you to take care when handling PhantomJS errors to ensure that you exit the PhantomJS script, whether the script was successfully executed or not. If you do not call ``phantom.exit(1);`` then PhantomJS will continue to run until your PHP script times out. If you find that your custom script is hanging then this is most likely the cause.

It is a good practice to create a global error handler in your script
that exits PhantomJS:

.. code:: javascript


        // my_procedure.proc

        phantom.onError = function(msg, trace) {
      
            phantom.exit(1);
        };
        
        ...

Using custom request parameters in your script
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Before a procedure is executed by the application it is parsed through a
template parser. The PHP PhantomJS library uses the popular `Twig
templating engine <https://github.com/fabpot/Twig>`__. This gives you
access to all the `Twig
niceness <http://twig.sensiolabs.org/doc/templates.html>`__ which you
can use in your custom scripts.

You may have noticed in the example above that we have used some Twig
template tags referencing a request object e.g.
``{{ request.getUrl() }}``. This is in fact the PHP request instance
that you created and passed to the client when sending your request,
which is injected into the Twig template parser. As a result you gain
full access to all the data contained within the request instance, via
the data accessor methods.

A default request instance contains the following accessors:

+--------------------------+-----------------------------------------------+------------------------------------+
| Accessor                 | Description                                   | Twig example                       |
+==========================+===============================================+====================================+
| getMethod()              | The request method e.g. GET.                  | {{ request.getMethod() }}          |
+--------------------------+-----------------------------------------------+------------------------------------+
| getTimeout()             | The request timeout period in milliseconds.   | {{ request.getTimeout() }}         |
+--------------------------+-----------------------------------------------+------------------------------------+
| getDelay()               | The page render delay in seconds.             | {{ request.getDelay() }}           |
+--------------------------+-----------------------------------------------+------------------------------------+
| getViewportWidth()       | The viewport width.                           | {{ request.getViewportWidth() }}   |
+--------------------------+-----------------------------------------------+------------------------------------+
| getViewportHeight()      | The viewport height.                          | {{ request.getViewportHeight() }}  |
+--------------------------+-----------------------------------------------+------------------------------------+
| getUrl()                 | The request URL.                              | {{ request.getUrl() }}             |
+--------------------------+-----------------------------------------------+------------------------------------+
| getBody()                | The request body (POST, PUT).                 | {{ request.getBody() }}            |
+--------------------------+-----------------------------------------------+------------------------------------+
| getHeaders(\ *format*)   | The request headers.                          | {{ request.getHeaders('json') }}   |
+--------------------------+-----------------------------------------------+------------------------------------+

A capture request contains a few additional ones:

+--------------------+-------------------------------------------+----------------------------------+
| Accessor           | Description                               | Twig example                     |
+====================+===========================================+==================================+
| getRectTop()       | The x coordinate of the capture region.   | {{ request.getRectTop() }}       |
+--------------------+-------------------------------------------+----------------------------------+
| getRectLeft()      | The y coordinate of the capture region.   | {{ request.getRectLeft() }}      |
+--------------------+-------------------------------------------+----------------------------------+
| getRectWidth()     | The width of the capture region.          | {{ request.getRectWidth() }}     |
+--------------------+-------------------------------------------+----------------------------------+
| getRectHeight()    | The height of the capture region.         | {{ request.getRectHeight() }}    |
+--------------------+-------------------------------------------+----------------------------------+
| getCaptureFile()   | The file to save the capture to.          | {{ request.getCaptureFile() }}   |
+--------------------+-------------------------------------------+----------------------------------+

If you would like to inject additional data into your script through
custom accessors, simply extend the request class with your own:

.. code:: php


        <?php

        use JonnyW\PhantomJs\Message\Request;
        
        class CustomRequest extends Request
        {
        
            public function getSomething()
            {
                return 'Something!';
            }
        }

Now you will be able to access the data in your custom script when using
your custom request:

.. code:: javascript

        
        // my_procedure.proc

        var something = '{{ request.getSomething() }}'; // Get something
        
        ...
        

And to use your custom request simply create a new instance of it and
pass it to the client:

.. code:: php


        <?php

        use JonnyW\PhantomJs\Client;
        
        $client = Client::getInstance();
        
        $response = $client->getMessageFactory()->createResponse();
        
        $request  = new CustomRequest();
        $request->setMethod('GET');
        $request->setUrl('http://www.google.com');
        
        $client->send($request, $response);

Loading your script
~~~~~~~~~~~~~~~~~~~

Now that you have your custom script and you've added your custom
request parameters, you may be wondering how to tell the client to
actually load your script. This is done by creating a procedure loader
and telling it where to find your script files.

The service container has a factory that makes creating a new procedure
loader easy:

.. code:: php


        <?php
        
        use JonnyW\PhantomJs\Client;
        use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
        
        $location = '/path/to/your/procedure/directory';
        
        $serviceContainer = ServiceContainer::getInstance();
        
        $procedureLoader = $serviceContainer->get('procedure_loader_factory')
            ->createProcedureLoader($location);
            
        ...

The client contains a chain procedure loader which lets you set multiple
loaders at the same time. Ultimately this means that you can load your
custom scripts while still maintaining the ability to load the default
scripts if you choose.

Now add your procedure loader to the chain loader:

.. code:: php


        <?php

        ...
        
        $client = Client::getInstance();
        $client->getProcedureLoader()->addLoader($procedureLoader);
        
        ...

The last thing you need to do is to tell the request which script you
want to load for that request. This is done by setting the request type
to the name of your procedure file, minus the extension:

.. code:: php


        <?php

        ...
        
       $request = $client->getMessageFactory()->createRequest();
       $request->setType('my_procedure');
        
        ...

Or if you are using a custom request as outlined in the `custom request
parameters <#using-custom-request-parameters-in-your-script>`__ section,
you can implement a ``getType()`` method which returns the name of your
procedure, eliminating the need to set the request type for each
request:

.. code:: php


    <?php

        use JonnyW\PhantomJs\Message\Request;
        
        class CustomRequest extends Request
        {
        
            public function getType()
            {
                return 'my_procedure';
            }
        }

Below is a full example for clarity:

.. code:: php


        <?php
        
        use JonnyW\PhantomJs\Client;
        use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
        
        $location = '/path/to/your/procedure/directory';
        
        $serviceContainer = ServiceContainer::getInstance();
        
        $procedureLoader = $serviceContainer->get('procedure_loader_factory')
            ->createProcedureLoader($location);
            
        $client = Client::getInstance();
        $client->getProcedureLoader()->addLoader($procedureLoader);
        
        $request = $client->getMessageFactory()->createRequest();
        $request->setType('my_procedure');
        
        $response = $client->getMessageFactory()->createResponse();
        
        $client->send($request, $response);

.. important::
   If you find that your script isn't running or that you are receiving a status of '0' back in the response, chances are you have a syntax error in you script. It pays to turn debugging on in the client ``$client->debug(true)`` which will then give you access to some log information through ``$client->getLog()``.

See more detailed information about
`troubleshooting https://github.com/jonnnnyw/php-phantomjs/blob/master/doc/troubleshooting.rst>`__.