<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\IO;

use JonnyW\PhantomJs\Page\PaperSize;
use JonnyW\PhantomJs\Page\ZoomFactor;
use JonnyW\PhantomJs\Page\ViewportSize;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
trait OutputTrait
{
    /**
     * Output logs.
     *
     * @var array
     */
    private $logs = [];

    /**
     * Page setup.
     *
     * @var array
     */
    private $page = [];

    /**
     * Create new output instance
     * with viewport size set.
     *
     * @param \JonnyW\PhantomJs\Page\ViewportSize $size
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withViewportSize(ViewportSize $size)
    {
        $new = clone $this;
        $new->page['viewportSize'] = $size;

        return $new;
    }

    /**
     * Create new output instance
     * and unset viewport size.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutViewportSize()
    {
        $new = clone $this;

        unset($new->page['viewportSize']);

        return $new;
    }

    /**
     * Get viewport size.
     *
     * @return \JonnyW\PhantomJs\Page\ViewportSize
     */
    public function getViewportSize()
    {
        return $this->page['viewportSize'];
    }

    /**
     * Create new output instance
     * with paper size set.
     *
     * @param \JonnyW\PhantomJs\Page\PaperSize $size
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withPaperSize(PaperSize $size)
    {
        $new = clone $this;
        $new->page['paperSize'] = $size;

        return $new;
    }

    /**
     * Create new output instance
     * and unset paper size.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutPaperSize()
    {
        $new = clone $this;

        unset($new->page['paperSize']);

        return $new;
    }

    /**
     * Get paper size.
     *
     * @return \JonnyW\PhantomJs\Page\PaperSize
     */
    public function getPaperSize()
    {
        return $this->page['paperSize'];
    }

    /**
     * Create new output instance
     * with zoom factor set.
     *
     * @param \JonnyW\PhantomJs\Page\ZoomFactor $zoom
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withZoomFactor(ZoomFactor $zoom)
    {
        $new = clone $this;
        $new->page['zoomFactor'] = $zoom;

        return $new;
    }

    /**
     * Create new output instance
     * and unset zoom factor.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutZoomFactor()
    {
        $new = clone $this;

        unset($new->page['zoomFactor']);

        return $new;
    }

    /**
     * Get zoom factor.
     *
     * @return \JonnyW\PhantomJs\Page\ZoomFactor
     */
    public function getZoomFactor()
    {
        return $this->page['zoomFactor'];
    }

    /**
     * Create new output instance
     * with clip rect set.
     *
     * @param \JonnyW\PhantomJs\Page\ClipRect $clipRect
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withClipRect(ClipRect $clipRect)
    {
        $new = clone $this;
        $new->page['clipRect'] = $clipRect;

        return $new;
    }

    /**
     * Create new output instance
     * and unset clip rect.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutClipRect()
    {
        $new = clone $this;

        unset($new->page['clipRect']);

        return $new;
    }

    /**
     * Get clip rect.
     *
     * @return \JonnyW\PhantomJs\Page\ClipRect
     */
    public function getClipRect()
    {
        return $this->page['clipRect'];
    }

    /**
     * Create new output instance
     * with log entry added.
     *
     * @param string $line
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withLog($entry)
    {
        $new = clone $this;
        $new->logs[] = $entry;

        return $new;
    }

    /**
     * Get log data.
     *
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }
}
