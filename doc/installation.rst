Installation
============

- `Prerequisites <#prerequisites>`__
- `Installing via Composer <#installing-via-composer>`__
- `Custom Installation <#custom-installation>`__
- `Installing from tarball <#installing-from-tarball>`__

Prerequisites
-------------

PHP PhantomJS requires PHP **5.3.0** or greater to run.

Installing via Composer
-----------------------

Install `Composer <https://getcomposer.org/>`__ for your project:

.. code:: shell

        $ curl -s http://getcomposer.org/installer | php

Create a ``composer.json`` file in the root of your project:

.. code:: yaml

        {
            "require": {
                "jonnyw/php-phantomjs": "3.*"
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

It is important that you have the 'scripts' section shown above in your
``composer.json`` file as it will install the latest version of
PhantomJS for your system to your project's bin folder. It is
recommended that you create a bin folder in the root of your project as
this is where the PHP PhantomJS library will look for your PhantomJS
executable. If you would prefer to use a PhantomJS executable in a
custom location, see the `Custom Installation <#custom-installation>`__
section.

Finally, install the composer depedencies for your project:

.. code:: shell
        
        #bash
        
        $ php composer.phar install

Custom Installation
-------------------

If you would prefer to use a custom install location for the PhantomJS
executable, you simply need to tell the client where to find the
executable file:

.. code:: php

        use JonnyW\PhantomJs\Client;

        $client = Client::getInstance();
        $client->setPhantomJs('/path/to/phantomjs');

If you would like composer to install the PhantomJS executable to a
custom location when installing dependencies, set the bin dir location
in your project's ``composer.json`` file:

.. code:: yaml

        {
            "config": {
                "bin-dir": "/path/to/your/projects/bin/dir"
            }
        }

You will need to make sure that this directory exists and is writable by
Composer before running the composer install.

Once you have updated your bin location run composer install to install
PhantomJS:

.. code:: shell
        
        #bash
        
        $ php composer.phar install

This should install the correct PhantomJS executable for your system to
the bin locaiton you defined in your ``composer.json`` file. As
mentioned above, you will need to tell the client where to find your
PhantomJS executable as it is not installed in the default location:

.. code:: php

        use JonnyW\PhantomJs\Client;

        $client = Client::getInstance();
        $client->setPhantomJs('/path/to/phantomjs');

Installing from tarball
-----------------------

The PHP PhantomJS library contains several depedencies in order to
function so it is recommended that you install it via composer as this
will handle your dependencies for you. If you do wish to install it from
a `tarball release <https://github.com/jonnnnyw/php-phantomjs/tags>`__
then you will need to install the dependencies manually.

The PHP PhantomJS library currently requires the following depdencies:

-  `Symfony Config Component <https://github.com/symfony/Config>`__ ~2.5
-  `Symfony Dependency Injection
   Component <https://github.com/symfony/DependencyInjection>`__ ~2.5
-  `Symfony Filesystem
   Component <https://github.com/symfony/filesystem>`__ ~2.5
-  `Twig templating Component <https://github.com/fabpot/Twig>`__ ~1.16
-  `PhantomJS <http://phantomjs.org/>`__ ~1.9

Make sure the components are in your include path and that the PhantomJS
executable is installed to your projects bin folder as mentioned in the
`Custom Installation <#custom-installation>`__ section.