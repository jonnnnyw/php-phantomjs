<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Test;

use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Get dependency injection container.
     *
     * @access public
     * @return \JonnyW\PhantomJs\DependencyInjection\ServiceContainer
     */
    public function getContainer()
    {
        return ServiceContainer::getInstance();
    }
}
