<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Parser;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class JsonParser implements ParserInterface
{
    /**
     * Parse json string into array.
     *
     * @access public
     * @param  string    $data
     * @return \stdClass
     */
    public function parse($data)
    {
        if ($data === null || !is_string($data)) {
            return array();
        }

        if (substr($data, 0, 1) !== '{' &&
            substr($data, 0, 1) !== '[') {
            return array();
        }

        return (array) json_decode($data, true);
    }
}
