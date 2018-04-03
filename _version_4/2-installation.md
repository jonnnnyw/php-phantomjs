---
layout: post
title: Installation
categories: []
tags: []
fullview: true
version: 4.0
---

* [Prerequisites](#prerequisites)
* [Installing via Composer](#installing-via-composer)
* [Custom Installation](#custom-installation)
* [Installing from tarball](#installing-from-tarball)

---

Prerequisites
-------------

PHP PhantomJS requires PHP **5.4.0** or greater to run.

Installing via Composer
-----------------------

Install [Composer](https://getcomposer.org/) for your project:

{% highlight bash %}

    #bash

    $ curl -s http://getcomposer.org/installer | php
{% endhighlight %}

Create a `composer.json` file in the root of your project:

{% highlight yaml %}

    #composer.json

    {
        "require": {
            "jonnyw/php-phantomjs": "4.*"
        },
        "config": {
            "bin-dir": "bin"
        },
        "scripts": {
            "post-install-cmd": [
                "PhantomInstaller\\Installer::installPhantomJS"
            ],
            "post-update-cmd": [
                "PhantomInstaller\\Installer::installPhantomJS"
            ]
        }
    }
{% endhighlight %}

It is important that you have the 'scripts' section shown above in your `composer.json` file as it will install the latest version of PhantomJS for your system to your project's bin folder. It is recommended that you create a bin folder in the root of your project as this is where the PHP PhantomJS library will look for your PhantomJS executable. If you would prefer to use a PhantomJS executable in a custom location, see the [Custom Installation](#custom-installation) section.

Finally, install the composer depedencies for your project:

{% highlight bash %}
    
    #bash
    
    $ php composer.phar install
{% endhighlight %}

Custom Installation
-------------------

If you would prefer to use a custom install location for the PhantomJS executable, for example you have PhantomJS installed globally, then you simply need to set the path to PhantomJS manually:

{% highlight php %}
    
    <?php
    
    use JonnyW\PhantomJs\Client;

    $client = Client::getInstance();
    $client->getEngine()->setPath('/path/to/phantomjs');
{% endhighlight %}

If you would like composer to install the PhantomJS executable to a custom bin location when installing dependencies, set the bin dir location in your project's `composer.json` file:

{% highlight yaml %}

    #composer.json

    {
        "config": {
            "bin-dir": "/path/to/your/projects/bin/dir"
        }
    }
{% endhighlight %}

You will need to make sure that this directory exists and is writable by Composer before running the composer install.

Once you have updated your bin location run composer install to install PhantomJS:

{% highlight bash %}
    
    #bash
    
    $ php composer.phar install
{% endhighlight %}

This should install the correct PhantomJS executable for your system in the bin locaiton you defined in your `composer.json` file. 


Installing from tarball
-----------------------

The PHP PhantomJS library contains several depedencies in order to function so it is recommended that you install it via composer as this will handle your dependencies for you. If you do wish to install it from a [tarball release](https://github.com/jonnnnyw/php-phantomjs/tags) then you will need to install the dependencies manually.

The PHP PhantomJS library currently requires the following depdencies:

-  [Symfony Config Component](https://github.com/symfony/Config) ~2.5
-  [Symfony YAML Component](https://github.com/symfony/Yaml) ~2.5
-  [Symfony Dependency Injection Component](https://github.com/symfony/DependencyInjection) ~2.5
-  [Symfony Filesystem Component](https://github.com/symfony/filesystem) ~2.5
-  [Twig templating Component](https://github.com/fabpot/Twig) ~1.16
-  [PhantomJS](http://phantomjs.org/) ~1.9

Make sure the components are in your include path and that the PhantomJS executable is installed to your projects bin folder as mentioned in the [Custom Installation](#custom-installation) section.
