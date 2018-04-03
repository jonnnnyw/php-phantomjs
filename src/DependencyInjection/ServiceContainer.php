<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ServiceContainer extends ContainerBuilder
{
    /**
     * Service container instance.
     *
     * @var \JonnyW\PhantomJs\DependencyInjection\ServiceContainer
     */
    private static $instance;

    /**
     * Get singleton instance.
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
            self::$instance->load();
        }

        return self::$instance;
    }

    /**
     * Load service container.
     */
    public function load($file = null)
    {
        $loader = new YamlFileLoader($this, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        $this->setParameter('phantomjs.cache_dir', sys_get_temp_dir());
        $this->setParameter('phantomjs.resource_dir', __DIR__.'/../Resources');
    }
}
