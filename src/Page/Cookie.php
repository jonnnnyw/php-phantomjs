<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Page;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Cookie implements \JsonSerializable
{
    /**
     * Name.
     *
     * @var string
     */
    private $name;

    /**
     * Value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Path.
     *
     * @var string
     */
    private $path;

    /**
     * Domain.
     *
     * @var string
     */
    private $domain;

    /**
     * HTTP Only.
     *
     * @var bool
     */
    private $httpOnly;

    /**
     * Secure.
     *
     * @var bool
     */
    private $secure;

    /**
     * Expires.
     *
     * @var int
     */
    private $expires;

    /**
     * Internal constructor.
     *
     * @param string $name
     * @param mixed  $value
     * @param string $path
     * @param string $domain
     * @param bool   $httpOnly (default: true)
     * @param bool   $secure   (default: false)
     * @param int    $expires  (default: null)
     */
    public function __construct($name, $value, $path, $domain, $httpOnly = true, $secure = false, $expires = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->path = $path;
        $this->domain = $domain;
        $this->httpOnly = $httpOnly;
        $this->secure = $secure;
        $this->expires = $expires;
    }

    /**
     * Format data for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(array(
            'name' => $this->name,
            'value' => $this->value,
            'path' => $this->path,
            'domain' => $this->domain,
            'httponly' => $this->httpOnly,
            'secure' => $this->secure,
            'expires' => $this->expires,
        ));
    }
}
