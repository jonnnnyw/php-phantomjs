<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class PdfRequest extends CaptureRequest implements PdfRequestInterface
{
    /**
     * Paper width.
     *
     * @var int
     */
    protected $paperWidth;

    /**
     * Paper height.
     *
     * @var int
     */
    protected $paperHeight;

    /**
     * Format.
     *
     * @var string
     */
    protected $format;

    /**
     * Orientation.
     *
     * @var string
     */
    protected $orientation;

    /**
     * Margin.
     *
     * @var string|array
     */
    protected $margin;

    /**
     * Repeating header.
     *
     * @var array
     */
    protected $header;

    /**
     * Repeating footer.
     *
     * @var array
     */
    protected $footer;

    /**
     * Internal constructor.
     *
     * @param string $url     (default: null)
     * @param string $method  (default: RequestInterface::METHOD_GET)
     * @param int    $timeout (default: 5000)
     *
     * @return \JonnyW\PhantomJs\Http\CaptureRequest
     */
    public function __construct($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        parent::__construct($url, $method, $timeout);

        $this->paperWidth = '';
        $this->paperHeight = '';
        $this->margin = '1cm';
        $this->format = 'A4';
        $this->orientation = 'portrait';
        $this->header = array();
        $this->footer = array();
    }

    /**
     * Get request type.
     *
     * @return string
     */
    public function getType()
    {
        if (!$this->type) {
            return RequestInterface::REQUEST_TYPE_PDF;
        }

        return $this->type;
    }

    /**
     * Set paper width.
     *
     * @param string $width
     */
    public function setPaperWidth($width)
    {
        $this->paperWidth = $width;
    }

    /**
     * Get paper width.
     *
     * @return string
     */
    public function getPaperWidth()
    {
        return $this->paperWidth;
    }

    /**
     * Set paper height.
     *
     * @param string $height
     */
    public function setPaperHeight($height)
    {
        $this->paperHeight = $height;
    }

    /**
     * Get paper height.
     *
     * @return string
     */
    public function getPaperHeight()
    {
        return $this->paperHeight;
    }

    /**
     * Set paper size.
     *
     * @param string $width
     * @param string $height
     */
    public function setPaperSize($width, $height)
    {
        $this->paperWidth = $width;
        $this->paperHeight = $height;
    }

    /**
     * Set format.
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Get format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set orientation.
     *
     * @param string $orientation
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * Get orientation.
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set margin.
     *
     * @param string|array $margin
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    /**
     * Get margin.
     *
     * @return string|array
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * Set repeating header.
     *
     * @param string $content
     * @param string $height  (default: '1cm')
     */
    public function setRepeatingHeader($content, $height = '1cm')
    {
        $this->header = array(
            'content' => $content,
            'height' => $height,
        );
    }

    /**
     * Get repeating header.
     *
     * @return array
     */
    public function getRepeatingHeader()
    {
        return $this->header;
    }

    /**
     * Set repeating footer.
     *
     * @param string $content
     * @param string $height  (default: '1cm')
     */
    public function setRepeatingFooter($content, $height = '1cm')
    {
        $this->footer = array(
            'content' => $content,
            'height' => $height,
        );
    }

    /**
     * Get repeating footer.
     *
     * @return array
     */
    public function getRepeatingFooter()
    {
        return $this->footer;
    }
}
