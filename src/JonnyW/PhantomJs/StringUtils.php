<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
final class StringUtils
{
    /**
     * Generate random string
     *
     * @static
     * @access public
     * @param  int    $length (default: 20)
     * @return string
     */
    public static function random($length = 20)
    {
        return substr(md5(mt_rand()), 0, $length);
    }
}
