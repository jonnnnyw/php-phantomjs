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
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ServiceContainer extends ContainerBuilder
{
    /**
     * Service container instance
     *
     * @var \JonnyW\PhantomJs\DependencyInjection\ServiceContainer
     * @access private
     */
    private static $instance;

    /**
     * Get singleton instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Client
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof ServiceContainer) {

            self::$instance = new ServiceContainer();
            self::$instance->load();
        }

        return self::$instance;
    }

    /**
     * Load service container.
     *
     * @access public
     * @return void
     */
    public function load()
    {
        $loader = new YamlFileLoader($this, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        $this->setParameter('phantomjs.cache_dir', sys_get_temp_dir());
        $this->setParameter('phantomjs.resource_dir', __DIR__.'/../Resources');
    }
}
