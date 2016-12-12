<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

use GuzzleHttp\Psr7\Response as BaseResponse;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Response extends BaseResponse implements OutputInterface
{
    use \GuzzleHttp\Psr7\MessageTrait;
    use \JonnyW\PhantomJs\IO\Output\OutputTrait;

    /**
     * Get input type.
     *
     * @return string
     */
    public function getType()
    {
        return 'http';
    }
}
