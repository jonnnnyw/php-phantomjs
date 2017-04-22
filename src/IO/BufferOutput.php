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
class BufferOutput implements OutputInterface
{
    use \GuzzleHttp\Psr7\MessageTrait;
    use \JonnyW\PhantomJs\IO\OutputTrait;

    /**
     * Format.
     *
     * @var string
     */
    private $format;

    /**
     * Internal constructor.
     *
     * @param string $format (default: 'PNG')
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($format = 'PNG')
    {
        $formats = array(
            'PNG',
            'GIF',
            'JPEG',
            'PDF',
        );

        if (!in_array(strtoupper($format), $formats)) {
            throw new \InvalidArgumentException('Output format must be PNG, GIF, JPEG or PDF');
        }

        $this->format = strtoupper($format);
    }
    
    /**
     * Get format.
     * 
     * @access public
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Get output type.
     *
     * @return string
     */
    public function getType()
    {
        return 'buffer';
    }
}
