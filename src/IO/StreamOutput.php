<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\IO;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class StreamOutput implements OutputInterface
{
    use \GuzzleHttp\Psr7\MessageTrait;
    use \JonnyW\PhantomJs\IO\OutputTrait;

    /**
     * Internal constructor.
     *
     * @param string|null|resource|StreamInterface $body
     */
    public function __construct($body)
    {
        $this->stream = \GuzzleHttp\Psr7\stream_for($body);
    }

    /**
     * Get output type.
     *
     * @return string
     */
    public function getType()
    {
        return 'stream';
    }
}
