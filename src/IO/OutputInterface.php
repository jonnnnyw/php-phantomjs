<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\IO;

use Psr\Http\Message\MessageInterface;
use JonnyW\PhantomJs\Page\PaperSize;
use JonnyW\PhantomJs\Page\ZoomFactor;
use JonnyW\PhantomJs\Page\ViewportSize;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface OutputInterface extends MessageInterface
{
    /**
     * Create new output instance
     * with viewport size set.
     *
     * @param \JonnyW\PhantomJs\Page\ViewportSize $size
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withViewportSize(ViewportSize $size);

    /**
     * Create new output instance
     * and unset viewport size.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutViewportSize();

    /**
     * Get viewport size.
     *
     * @return \JonnyW\PhantomJs\Page\ViewportSize
     */
    public function getViewportSize();

    /**
     * Create new output instance
     * with paper size set.
     *
     * @param \JonnyW\PhantomJs\Page\PaperSize $size
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withPaperSize(PaperSize $size);

    /**
     * Create new output instance
     * and unset paper size.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutPaperSize();

    /**
     * Get paper size.
     *
     * @return \JonnyW\PhantomJs\Page\PaperSize
     */
    public function getPaperSize();

    /**
     * Create new output instance
     * with zoom factor set.
     *
     * @param \JonnyW\PhantomJs\Page\ZoomFactor $zoom
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withZoomFactor(ZoomFactor $zoom);

    /**
     * Create new output instance
     * and unset zoom factor.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutZoomFactor();

    /**
     * Get zoom factor.
     *
     * @return \JonnyW\PhantomJs\Page\ZoomFactor
     */
    public function getZoomFactor();

    /**
     * Create new output instance
     * with clip rect set.
     *
     * @param \JonnyW\PhantomJs\Page\ClipRect $zoom
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withClipRect(ClipRect $zoom);

    /**
     * Create new output instance
     * and unset clip rect.
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withoutClipRect();

    /**
     * Get clip rect.
     *
     * @return \JonnyW\PhantomJs\Page\ClipRect
     */
    public function getClipRect();

    /**
     * Create new output instance
     * with log entry added.
     *
     * @param string $line
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function withLog($entry);

    /**
     * Get log data.
     *
     * @return array
     */
    public function getLogs();

    /**
     * Get input type.
     *
     * @return string
     */
    public function getType();
}
