---
layout: post
title: Caching
categories: []
tags: []
fullview: true
version: 4.0
---

* [Script caching](#script-caching)
* [Clearing the cache](#clearing-the-cache)
* [Disabling the cache](#disabling-the-cache)

---

Script caching
--------------

By default the PHP PhantomJs library compiles and aggressively caches script templates the first time that they are run. This means that if you override any [partial scripts]({{ site.BASE_PATH }}/4.0/custom-scripts/) after the script template has been cached by the library then the changes will not take affect until you [clear the compiled cache](#clearing-the-cache).

It is recommended that you [disable the compiler cache](#disabling-the-cache) while developing your own custom scripts.

> #### Note
> The default cache location is the system tmp directory. The location of this directory can be found through the `sys_get_temp_dir()` PHP directive.

Disabling the cache
-------------------

The compiler cache can be easily disabled. When disabled script templates will be compiled for each request and no caching will take place.

{% highlight php %}
    
    <?php
    
    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    $client->getProcedureCompiler()->disableCache();

{% endhighlight %}

The compiler cache is enabled by default however you can enable it manually if the situation arises.

{% highlight php %}
    
    <?php
    
    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    $client->getProcedureCompiler()->enableCache();

{% endhighlight %}

> #### Important
> Script caching greatly improves performance therefore you should ensure that the cache is enabled in any production environment or in situtations where performance is important.

Clearing the cache
------------------

The compiler cache can be easily cleared. This will force script templates to be recompiled and cached on the next request.

{% highlight php %}
    
    <?php
    
    use JonnyW\PhantomJs\Client;
    
    $client = Client::getInstance();
    $client->getProcedureCompiler()->clearCache();

{% endhighlight %}