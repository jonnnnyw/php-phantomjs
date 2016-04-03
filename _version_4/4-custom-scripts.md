---
layout: post
title: Custom Scripts
categories: []
tags: []
fullview: true
version: 4.0
---

  * [Custom PhantomJS scripts](#custom-phantomjs-scripts)
  * [Partial script injection](#partial-script-injection)
  * [Writing a custom template](#writing-a-custom-template)
  * [Using custom request parameters in your script](#using-custom-request-parameters-in-your-script)

---

Custom PhantomJS scripts
------------------------

In most cases you shouldn't need to worry about the javascript files that run the PHP PhantomJS library but there may be times when you want to execute your own custom PhantomJS scripts through the client. This can be easily achieved in 2 ways - either through [partial script injection](#partial-script-injection) or by [writing your own custom template](#writing-a-custom-template).

When PhantomJS performs a request it loads a script template file and builds a PhantomJS script by injecting small blocks of javascript code into the template. These partial code blocks inject functionality such as setting the viewport size, capture options, error handling etc. These code blocks may be overridden allowing you to manipulate the way requests are performed, with little effort.

Alternatively you can write your own script template changing how PHP PhantomJS executes scripts altogether. This requires more work but can be very powerful.

> #### Note
>  PHP PhantomJS compiles, validates and caches scripts when they are first run to greatly improve performance. If you would like to clear this cache or disable it while you are writing your own scripts then refer to the [caching section]({{ site.BASE_PATH }}/4.0/caching/).

Partial script injection
------------------------

Partial script injection is the easiest way to manipulate the scripts that are executed by PhantomJS. When PHP PhantomJS performs a request it loads a script template and compiles small blocks of javascript into the template before executing the script. These injected blocks contain different pieces of PhantomJS functionality such as setting the viewport size, capture options, logging and error handling etc. You can override as many of these blocks as you please to change they way a script functions.

The following outlines the partial blocks that are compiled into a PHP PhantomJS template by default.

| Block Name                                   | Description                                                                                      |
| -------------------------------------------- | ------------------------------------------------------------------------------------------------ |
| [global_variables.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/global_variables.partial)          | Allows any javascript variables to be injected at the top of the script.                         |
| [page_clip_rect.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_clip_rect.partial)            | If the request is a screen capture, this will define the page clipping rectangle.                |
| [page_custom_headers.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_custom_headers.partial)       | Set any custom headers on the page object.                                                       |
| [page_on_error.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_on_error.partial)             | Defines the code that is executed on page error.                                                 |
| [page_on_resource_received.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_on_resource_received.partial) | Defines the code that is executed on resource receive.                                           |
| [page_on_resource_timeout.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_on_resource_timeout.partial)  | Defines the code that is executed on resource timeout.                                           |
| [page_open.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_open.partial)                 | Defines the code that is executed on page open.                                                  |
| [page_paper_size.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_paper_size.partial)           | If the request is PDF output, this will set up the paper size.                                   |
| [page_settings.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_settings.partial)             | Defines any page settings most notably the resource timeout value.                               |
| [page_viewport_size.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/page_viewport_size.partial)        | Set up the viewport size if defined in the request.                                              |
| [phantom_on_error.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/phantom_on_error.partial)          | Defines the code that is execute on PhantomJS error.                                             |
| [procedure_capture.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/procedure_capture.partial)         | Defines the code that is executed if the request is a capture request.                           |
| [procedure_default.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/procedure_default.partial)         | Defines the code that is executed for a default request.                                         |
| [procedure_pdf.partial](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/procedure_pdf.partial)             | Defines the code that is executed if the request is a PDF request.                               |


> #### Note
>  It may pay to check out the [default script template](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/http_default.proc) to see where each of these blocks are rendered when compiling the PhantomJS script.

To override a partial block with your own code first you need to create a file with the same name as the block that you are overriding. Make sure that this file can be read by your application.

{% highlight bash %}
    
    #bash
    
    $ touch phantom_on_error.partial
    $ chmod 755 phantom_on_error.partial
    
{% endhighlight %}

Next open your partial block in your text editor and write the code you wish to execute. The [PhantomJS documentation](http://phantomjs.org/quick-start.html) has more detailed information on writing custom scripts.

{% highlight javascript %}
    
    {% raw %}
    
    // phantom_on_error.partial
    
    var error = {
      msg: 'There was an error!'  
    };
    
    system.stdout.write(JSON.stringify(error));
    phantom.exit(1);

    ...
    
    {% endraw %}
    
{% endhighlight %}


Now you need to tell PHP PhantomJS where to look for your partial block. This is achieved by creating a procedure loader that points at your custom script directory. The service container has a factory that makes creating a new procedure loader easy.

{% highlight php %}

    <?php
    
    use JonnyW\PhantomJs\Client;
    use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
    
    $location = '/path/to/your/script/directory';
    
    $serviceContainer = ServiceContainer::getInstance();
    
    $procedureLoader = $serviceContainer->get('procedure_loader_factory')
        ->createProcedureLoader($location);
        
    ...
{% endhighlight %}


Finally add your procedure loader to the load loop. By default the client contains a chain procedure loader which lets you maintain multiple procedure loaders. Ultimately this means that you can load your custom script blocks while still maintaining the ability to load the default ones.

So now just add the procedure loader that you created above to the chain loader.

{% highlight php %}

    <?php

    ...
    
    $client = Client::getInstance();
    $client->getProcedureLoader()->addLoader($procedureLoader);
    
    ...
{% endhighlight %}

> #### Note
> If multiple procedure loaders are defined, PHP PhantomJS will alwasy look for custom scripts first before falling back on the default ones.

Now whenever you perform a request PHP PhantomJS will look in your script directory first for any partial blocks to inject. You can override as many script blocks as you wish but be aware that in doing so you may limit or break the functionality of the PHP PhantomJS library.

Below is a full example for clarity.

{% highlight php %}

    <?php
    
    use JonnyW\PhantomJs\Client;
    use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
    
    $location = '/path/to/your/script/directory';
    
    $serviceContainer = ServiceContainer::getInstance();
    
    $procedureLoader = $serviceContainer->get('procedure_loader_factory')
        ->createProcedureLoader($location);
        
    $client = Client::getInstance();
    $client->getProcedureLoader()->addLoader($procedureLoader);
    
    $request  = $client->getMessageFactory()->createRequest();
    $response = $client->getMessageFactory()->createResponse();
    
    $client->send($request, $response);

{% endhighlight %}

> #### Note
> PHP PhantomJS compiles and caches scripts when they are first run to greatly improve performance. This cache can be easily [cleared]({{ site.BASE_PATH }}/4.0/caching/#clearing-the-cache) or [disabled]({{ site.BASE_PATH }}/4.0/caching/#disabling-the-cache) while you are developing your own custom scripts. Do be aware that script execution time will be greatly increased by disabling the cache.

> #### Note
> Scripts are validated using a Javascript validation engine when they are compiled. For help debugging validation errors see the [debugging]({{ site.BASE_PATH }}/4.0/debugging/#validation-errors) section.


Writing a custom template
-------------------------

The script template that is run by PHP PhantomJS on each request can be easily replaced with your own version. This requires more work to get right but can be very powerful.

The first step in creating your script is to create a procedure file somewhere. For the purpose of this guide we will refer to it as `my_procedure.proc` but in reality it can be called anything you like. The only requirement is that the file extension must be `.proc`.

Create the file somewhere and make sure it can be read by your application.

{% highlight bash %}
    
    #bash
    
    $ touch my_procedure.proc
    $ chmod 755 my_procedure.proc
    
{% endhighlight %}

Next open your procedure file in a text editor and write your PhantomJS script. The [PhantomJS documentation](http://phantomjs.org/quick-start.html) has more detailed information on writing scripts for PhantomJS.

{% highlight javascript %}
    
    {% raw %}
    
    // my_procedure.proc

    var page  = require('webpage').create();
    
    page.open ('{{ input.getUrl() }}', '{{ input.getMethod() }}', '{{ input.getBody() }}', function (status) {
         
        // It is important that you exit PhantomJS
        // when your script has run or when you
        // encounter an error
        phantom.exit(1);
    });
    
    ...
    
    {% endraw %}
    
{% endhighlight %}

> #### Important
> Make sure that `phantom.exit(1);` is always called after your script has run or if you encounter an error. This requires you to take care when handling PhantomJS errors to ensure that you exit the PhantomJS script, whether the script was successfully executed or not. If you do not call `phantom.exit(1);` then PhantomJS will continue to run until your PHP script times out. You will most likely receive a validation error if you omit this from your script anyway.

It is a good practice to create a global error handler in your script that exits PhantomJS.

{% highlight javascript %}
    
    {% raw %}
    
    // my_procedure.proc

    phantom.onError = function(msg, trace) {
  
        phantom.exit(1);
    };
    
    ...
    
    {% endraw %}
    
{% endhighlight %}

As with the overriding of partial blocks mentioned earlier in this section, you need to tell PHP PhantomJS where to look for script template. This is achieved by creating a procedure loader that points at your custom script directory. The service container has a factory that makes creating a new procedure loader easy.

{% highlight php %}

    <?php
    
    use JonnyW\PhantomJs\Client;
    use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
    
    $location = '/path/to/your/script/directory';
    
    $serviceContainer = ServiceContainer::getInstance();
    
    $procedureLoader = $serviceContainer->get('procedure_loader_factory')
        ->createProcedureLoader($location);
        
    ...
{% endhighlight %}


Now add your procedure loader to the load loop. By default the client contains a chain procedure loader which lets you maintain multiple procedure loaders. Ultimately this means that you can load your custom templates while still maintaining the ability to load the default ones.

So now just add the procedure loader that you created above to the chain loader.

{% highlight php %}

    <?php

    ...
    
    $client = Client::getInstance();
    $client->getProcedureLoader()->addLoader($procedureLoader);
    
    ...
{% endhighlight %}

Finally you need to tell the client which procedure template to load when making a request. The template name is the name of your procedure template file minus the `.proc` part.

{% highlight php %}

    <?php

    ...
    
    $client = Client::getInstance();
    $client->setProcedure('my_procedure');
    
    ...
{% endhighlight %}

Below is a full example for clarity.

{% highlight php %}

    <?php
    
    use JonnyW\PhantomJs\Client;
    use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
    
    $location = '/path/to/your/script/directory';
    
    $serviceContainer = ServiceContainer::getInstance();
    
    $procedureLoader = $serviceContainer->get('procedure_loader_factory')
        ->createProcedureLoader($location);
        
    $client = Client::getInstance();
    $client->setProcedure('my_procedure');
    $client->getProcedureLoader()->addLoader($procedureLoader);
    
    $request  = $client->getMessageFactory()->createRequest();
    $response = $client->getMessageFactory()->createResponse();
    
    $client->send($request, $response);

{% endhighlight %}

> #### Note
> You may choose to support [partial script blocks](#partial-script-injection) in your template. It is worth checking out the [default script template](https://github.com/jonnnnyw/php-phantomjs/blob/master/src/JonnyW/PhantomJs/Resources/procedures/http_default.proc) to get an idea on how this is achieved.

Using custom request parameters in your script
----------------------------------------------

Before a procedure is executed by the application it is parsed through a template parser. The PHP PhantomJS library uses the popular [Twig templating engine](https://github.com/fabpot/Twig). This gives you access to all the [Twig niceness](http://twig.sensiolabs.org/doc/templates.html) which you can use in your custom scripts.

You may have noticed in the [writing a custom template](#writing-a-custom-template) section that we have used some Twig template tags referencing an input object e.g. {% raw %}`{{ input.getUrl() }}`{% endraw %}. This is in fact the PHP request instance that you created and passed to the client when sending your request, which is injected into the Twig template parser. As a result you gain full access to all the data contained within the request instance, via the data accessor methods.

A default request instance contains the following accessors.

{% raw %}

| Accessor             | Description                                 |  Twig example                   |
| -------------------- | ------------------------------------------- | ------------------------------- |
| getMethod()          | The request method e.g. GET.                | {{ input.getMethod() }}         |
| getTimeout()         | The request timeout period in milliseconds. | {{ input.getTimeout() }}        |
| getDelay()           | The page render delay in seconds.           | {{ input.getDelay() }}          |
| getViewportWidth()   | The viewport width.                         | {{ input.getViewportWidth() }}  |
| getViewportHeight()  | The viewport height.                        | {{ input.getViewportHeight() }} |
| getUrl()             | The request URL.                            | {{ input.getUrl() }}            |
| getBody()            | The request body (POST, PUT).               | {{ input.getBody() }}           |
| getHeaders(*format*) | The request headers.                        | {{ input.getHeaders('json') }}  |

{% endraw %}

A capture request contains a few additional ones.

{% raw %}

| Accessor         | Description                             |  Twig example                |
| ---------------- | ----------------------------------------| ---------------------------- |
| getRectTop()     | The x coordinate of the capture region. | {{ input.getRectTop() }}     |
| getRectLeft()    | The y coordinate of the capture region. | {{ input.getRectLeft() }}    |
| getRectWidth()   | The width of the capture region.        | {{ input.getRectWidth() }}   |
| getRectHeight()  | The height of the capture region.       | {{ input.getRectHeight() }}  |
| getCaptureFile() | The file to save the capture to.        | {{ input.getCaptureFile() }} |

{% endraw %}

And a PDF request a few more.

{% raw %}

| Accessor         | Description                              |  Twig example                |
| ---------------- | -----------------------------------------| ---------------------------- |
| getPaperWidth()  | The width to save the PDF e.g. '20cm'.   | {{ input.getPaperWidth() }}  |
| getPaperHeight() | The height to save the PDF e.g. '20cm'.  | {{ input.getPaperHeight() }} |
| getFormat()      | The paper format e.g. 'A4'.              | {{ input.getFormat() }}      |
| getOrientation() | The orientation - portrait or landscape. | {{ input.getOrientation() }} |
| getMargin()      | The paper margin e.g. '1cm'.             | {{ input.getMargin() }}      |

{% endraw %}

If you would like to inject additional data into your script through custom accessors, simply extend the request class with your own.

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Message\Request;
    
    class CustomRequest extends Request
    {
    
        public function getSomething()
        {
            return 'Something!';
        }
    }
{% endhighlight %}

Now you will be able to access the data in your custom script when using your custom request.

{% highlight javascript %}
    
    {% raw %}
    
    // my_procedure.proc

    var something = '{{ input.getSomething() }}'; // Get something
    
    ...
    
    {% endraw %}
    
{% endhighlight %}

And to use your custom request simply create a new instance of it and pass it to the client.

{% highlight php %}

    <?php

    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    
    $response = $client->getMessageFactory()->createResponse();
    
    $request  = new CustomRequest();
    $request->setMethod('GET');
    $request->setUrl('http://www.google.com');
    
    $client->send($request, $response);
{% endhighlight %}

> #### Troubleshooting
> If you find that your script isn't running or that you are receiving a status of '0' back in the response, chances are you have a syntax error in you script. It pays to turn debugging on in the client `$client->debug(true)` which will then give you access to some log information through `$client->getLog()`.

See more detailed information about [troubleshooting]({{ site.BASE_PATH }}/4.0/troubleshooting/).
