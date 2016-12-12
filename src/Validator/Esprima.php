<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Validator;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Esprima implements EngineInterface
{
    /**
     * File locator.
     *
     * @var \Symfony\Component\Config\FileLocatorInterface
     */
    protected $locator;

    /**
     * Esprima file.
     *
     * @var string
     */
    protected $file;

    /**
     * Esprima script.
     *
     * @var string
     */
    protected $esprima;

    /**
     * Internal constructor.
     *
     * @param \Symfony\Component\Config\FileLocatorInterface $locator
     * @param string                                         $file
     */
    public function __construct($locator, $file)
    {
        $this->locator = $locator;
        $this->file = $file;
    }

    /**
     * Returns engine as string.
     *
     * @return string
     */
    public function toString()
    {
        $this->load();

        return $this->esprima;
    }

    /**
     * To string magic method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Load esprima script.
     *
     * @return string
     */
    public function load()
    {
        if (!$this->esprima) {
            $this->esprima = $this->loadFile(
                $this->locator->locate($this->file)
            );
        }

        return $this->esprima;
    }

    /**
     * Load procedure file content.
     *
     * @param string $file
     *
     * @return string
     */
    protected function loadFile($file)
    {
        return file_get_contents($file);
    }
}
