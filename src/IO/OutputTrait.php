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
     * Settings.
     *
     * @var array
     */
    private $settings = [];

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
        $new->settings['page.viewportSize'] = $size;

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

        unset($new->settings['page.viewportSize']);

        return $new;
    }

    /**
     * Get viewport size.
     *
     * @return \JonnyW\PhantomJs\Page\ViewportSize
     */
    public function getViewportSize()
    {
        return $this->settings['page.viewportSize'];
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
        $new->settings['page.paperSize'] = $size;

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

        unset($new->settings['page.paperSize']);

        return $new;
    }

    /**
     * Get paper size.
     *
     * @return \JonnyW\PhantomJs\Page\PaperSize
     */
    public function getPaperSize()
    {
        return $this->settings['page.paperSize'];
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
        $new->settings['page.zoomFactor'] = $zoom;

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

        unset($new->settings['page.zoomFactor']);

        return $new;
    }

    /**
     * Get zoom factor.
     *
     * @return \JonnyW\PhantomJs\Page\ZoomFactor
     */
    public function getZoomFactor()
    {
        return $this->settings['page.zoomFactor'];
    }

    /**
     * Create new output instance
     * with clip rect set.
     *
     * @param \JonnyW\PhantomJs\Page\ClipRect $zoom
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withClipRect(ClipRect $zoom)
    {
        $new = clone $this;
        $new->settings['page.clipRect'] = $zoom;

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

        unset($new->settings['page.clipRect']);

        return $new;
    }

    /**
     * Get clip rect.
     *
     * @return \JonnyW\PhantomJs\Page\ClipRect
     */
    public function getClipRect()
    {
        return $this->settings['page.clipRect'];
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
