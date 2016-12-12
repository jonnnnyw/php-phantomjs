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
     * @param string $name
     *
     * @return mixed
     */
    public function getCustom($name)
    {
        if (isset($this->custom[$name])) {
            return $this->custom[$name];
        }

        return '';
    }

    /**
     * Has cookie.
     *
     * @param string $cookie
     *
     * @return bool
     */
    public function hasCookie($cookie)
    {
        return isset($this->cookies['add'][$cookie]);
    }

    /**
     * Get single added cookie.
     *
     * @param string $cookie
     *
     * @return \JonnyW\PhantomJs\Page\Cookie|null
     */
    public function getCookie($cookie)
    {
        if (isset($this->cookies['add'][$cookie])) {
            return $this->cookies['add'][$cookie];
        }

        return null;
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
     * Has setting.
     *
     * @param string $setting
     *
     * @return bool
     */
    public function hasSetting($setting)
    {
        return isset($this->settings[$setting]);
    }

    /**
     * Get single setting.
     *
     * @param string $setting
     *
     * @return mixed
     */
    public function getSetting($setting)
    {
        if (isset($this->settings[$setting])) {
            return $this->settings[$setting];
        }

        return null;
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
