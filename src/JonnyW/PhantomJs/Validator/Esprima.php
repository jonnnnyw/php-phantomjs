<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Validator;

use Symfony\Component\Config\FileLocatorInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Esprima implements EngineInterface
{
    /**
     * File locator
     *
     * @var \Symfony\Component\Config\FileLocatorInterface
     * @access protected
     */
    protected $locator;

    /**
     * Esprima file.
     *
     * @var string
     * @access protected
     */
    protected $file;

    /**
     * Esprima script.
     *
     * @var string
     * @access protected
     */
    protected $esprima;

    /**
     * Internal constructor.
     *
     * @access public
     * @param  \Symfony\Component\Config\FileLocatorInterface $locator
     * @param  string                                         $file
     * @return void
     */
    public function __construct($locator, $file)
    {
        $this->locator = $locator;
        $this->file    = $file;
    }

    /**
     * Returns engine as string.
     *
     * @access public
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
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Load esprima script.
     *
     * @access public
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
     * @access protected
     * @param  string $file
     * @return string
     */
    protected function loadFile($file)
    {
        return file_get_contents($file);
    }
}
