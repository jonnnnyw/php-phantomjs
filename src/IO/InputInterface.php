<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\IO;

use Psr\Http\Message\MessageInterface;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface InputInterface extends MessageInterface
{
    /**
     * Create new input with
     * added custom setting.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withCustom($name, $value);

    /**
     * Create new input with
     * custom setting removed.
     *
     * @param string $name
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withoutCustom($name);

    /**
     * Get custom setting.
     *
     * @return array
     */
    public function getCustom();

    /**
     * Get all added cookies.
     *
     * @return array
     */
    public function getCookies();

    /**
     * Create new input with
     * added cookie.
     *
     * @param \JonnyW\PhantomJs\Page\Cookie $cookie
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withCookie(Cookie $cookie);

    /**
     * Create new input with cookie
     * removed and flagged for delete.
     *
     * @param string $cookie
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withoutCookie($cookie);

    /**
     * Get all settings.
     *
     * @return array
     */
    public function getSettings();

    /**
     * Create new input with
     * added setting.
     *
     * @param string $setting
     * @param mixed  $value
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withSetting($setting, $value);

    /**
     * Create new input with setting
     * removed.
     *
     * @param string $setting
     *
     * @return \JonnyW\PhantomJs\IO\InputInterface
     */
    public function withoutSetting($setting);

    /**
     * Get input type.
     *
     * @return string
     */
    public function getType();
}
