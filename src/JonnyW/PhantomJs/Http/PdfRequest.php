<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class PdfRequest extends CaptureRequest
    implements PdfRequestInterface
{
    /**
     * Paper width
     *
     * @var int
     * @access protected
     */
    protected $paperWidth;

    /**
     * Paper height
     *
     * @var int
     * @access protected
     */
    protected $paperHeight;

    /**
     * Format
     *
     * @var string
     * @access protected
     */
    protected $format;

    /**
     * Orientation
     *
     * @var string
     * @access protected
     */
    protected $orientation;

    /**
     * Margin
     *
     * @var string|array
     * @access protected
     */
    protected $margin;

    /**
     * Repeating header
     *
     * @var array
     * @access protected
     */
    protected $header;

    /**
     * Repeating footer
     *
     * @var array
     * @access protected
     */
    protected $footer;

    /**
     * Internal constructor
     *
     * @access public
     * @param  string                                $url     (default: null)
     * @param  string                                $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                   $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Http\CaptureRequest
     */
    public function __construct($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        parent::__construct($url, $method, $timeout);

        $this->paperWidth  = '';
        $this->paperHeight = '';
        $this->margin      = '1cm';
        $this->format      = 'A4';
        $this->orientation = 'portrait';
        $this->header      = array();
        $this->footer      = array();

    }

    /**
     * Get request type
     *
     * @access public
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
     * @access public
     * @param  string $width
     * @return void
     */
    public function setPaperWidth($width)
    {
        $this->paperWidth = $width;
    }

    /**
     * Get paper width.
     *
     * @access public
     * @return string
     */
    public function getPaperWidth()
    {
        return $this->paperWidth;
    }

    /**
     * Set paper height.
     *
     * @access public
     * @param  string $height
     * @return void
     */
    public function setPaperHeight($height)
    {
        $this->paperHeight = $height;
    }

    /**
     * Get paper height.
     *
     * @access public
     * @return string
     */
    public function getPaperHeight()
    {
        return $this->paperHeight;
    }

    /**
     * Set paper size.
     *
     * @access public
     * @param  string $width
     * @param  string $height
     * @return void
     */
    public function setPaperSize($width, $height)
    {
        $this->paperWidth  = $width;
        $this->paperHeight = $height;
    }

    /**
     * Set format.
     *
     * @access public
     * @param  string $format
     * @return void
     */
    public function setFormat($format)
    {
        $this->format = $format;
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
     * Set orientation.
     *
     * @access public
     * @param  string $orientation
     * @return void
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }

    /**
     * Get orientation.
     *
     * @access public
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set margin.
     *
     * @access public
     * @param  string|array $margin
     * @return void
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;
    }

    /**
     * Get margin.
     *
     * @access public
     * @return string|array
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * Set repeating header.
     *
     * @access public
     * @param  string $content
     * @param  string $height  (default: '1cm')
     * @return void
     */
    public function setRepeatingHeader($content, $height = '1cm')
    {
        $this->header = array(
            'content' => $content,
            'height'  => $height
        );
    }

    /**
     * Get repeating header.
     *
     * @access public
     * @return array
     */
    public function getRepeatingHeader()
    {
        return $this->header;
    }

    /**
     * Set repeating footer.
     *
     * @access public
     * @param  string $content
     * @param  string $height  (default: '1cm')
     * @return void
     */
    public function setRepeatingFooter($content, $height = '1cm')
    {
        $this->footer = array(
            'content' => $content,
            'height'  => $height
        );
    }

    /**
     * Get repeating footer.
     *
     * @access public
     * @return array
     */
    public function getRepeatingFooter()
    {
        return $this->footer;
    }
}
