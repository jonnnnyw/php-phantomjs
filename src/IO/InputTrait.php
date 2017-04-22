<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\IO;

use JonnyW\PhantomJs\Page\Cookie;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
trait InputTrait
{
    /**
     * Cookies.
     *
     * @var array
     */
    private $cookies = [
        'add' => [],
        'remove' => [],
    ];

    /**
     * Settings.
     *
     * (default value: [])
     *
     * @var array
     */
    private $settings = [];

    /**
     * Custom input settings.
     *
     * (default value: [])
     *
     * @var array
     */
    private $custom = [];

    /**
     * Create new input with
     * added custom setting.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withCustom($name, $value)
    {
        $new = clone $this;
        $new->custom[$name] = $value;

        return $new;
    }

    /**
     * Create new input with
     * custom setting removed.
     *
     * @param string $name
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withoutCustom($name)
    {
        $new = clone $this;

        unset($new->custom[$name]);

        return $new;
    }

    /**
     * Get custom setting.
     *
     * @return array
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * Get all cookies.
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Create new input with
     * added cookie.
     *
     * @param \JonnyW\PhantomJs\Page\Cookie $cookie
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withCookie(Cookie $cookie)
    {
        $new = clone $this;
        $new->cookies['add'][$cookie->getName()] = $cookie;

        return $new;
    }

    /**
     * Create new input with cookie
     * removed and flagged for delete.
     *
     * @param string $cookie
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withoutCookie($cookie)
    {
        $new = clone $this;
        $new->cookies['remove'][] = $cookie;

        unset($new->cookies['add'][$cookie]);

        return $new;
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Create new input with
     * added setting.
     *
     * @param string $setting
     * @param mixed  $value
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withSetting($setting, $value)
    {
        $new = clone $this;
        $new->settings[$setting] = $value;

        return $new;
    }

    /**
     * Create new input with setting
     * removed.
     *
     * @param string $setting
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withoutSetting($setting)
    {
        $new = clone $this;

        unset($new->settings[$setting]);

        return $new;
    }
}
