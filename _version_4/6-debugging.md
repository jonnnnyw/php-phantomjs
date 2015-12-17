---
layout: post
title: Debugging
categories: []
tags: []
fullview: true
version: 4.0
---

* [PhantomJS log](#phantomjs-log)
* [Javascript console log](#javascript-console-log)
* [Validation errors](#validation-errors)

---

PhantomJS log
-------------

Verbose logging can be enabled for PhantomJS by setting the debug flag on the client. This is the same as setting the PhantomJS `--debug=true` command line option.

{% highlight php %}
        
    <?php 
    
    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();
    $client->debug(true);
{% endhighlight %}

The client log can be inspected after making a request.

{% highlight php %}
        
    <?php 
    
    ...
    
    $client->getLog(); // String
    
    ...
{% endhighlight %}

> #### Note
> The client log contains some helpful information specific to the PhantomJS library. In some cases these are present in the log even if debugging is disabled.

Javascript console log
----------------------

The response object also provides access to a console log. Any javascript errors raised on the requested page will be present in the response console log.

{% highlight php %}
        
    <?php 
    
    ...
    
    $response->getConsole(); // Array
    
    ...
{% endhighlight %}

Validation errors
-----------------

Before a script template is compiled and cached it is validated using the [Esprima](http://) javascript validation engine. If the script fails to validate then a `JonnyW\PhantomJs\Exception\SyntaxException` will be raised. Debug information about any validation errors can be found by calling a `getErrors()` helper method on the exception instance. This will return an array of error information. 

{% highlight php %}

    <?php 
    
    // $exception->getErrors();    
    array(1) {
      array(5) {
        'lineNumber'  => 1,
        'column'      => 17,
        'index'       => 16,
        'description' => 'Unexpected token ;',
        'message'     => 'Line 1: Unexpected token ;'
      }
    }

{% endhighlight %}

> #### Important
> Due to a limitation in the validation logic, scripts are currently minified to a single line before validating. This makes the `lineNumber` value contained in the error output redundant. This is due to be fixed in a future release.
