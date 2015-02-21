<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Validator;

use Symfony\Component\Config\FileLocator;
use JonnyW\PhantomJs\Validator\Esprima;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class EsprimaTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test invalid argument exception is thrown
     * if file path is not local file.
     *
     * @access public
     * @return void
     */
    public function testInvalidArgumentExceptionIsThrownIfFilePathIsNotLocalFile()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $fileLocator = $this->getFileLocator();
        $esprima     = $this->getEsprima($fileLocator, 'http://example.com');

        $esprima->load();
    }

    /**
     * Test invalid argument exception is thrown if
     * file does not exist.
     *
     * @access public
     * @return void
     */
    public function testInvalidArgumentIsThrownIfFileDoesNotExist()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $fileLocator = $this->getFileLocator();
        $esprima     = $this->getEsprima($fileLocator, 'invalidFile.js');

        $esprima->load();
    }

    /**
     * Test engine can be loaded.
     *
     * @access public
     * @return void
     */
    public function testEngineCanBeLoaded()
    {
        $fileLocator = $this->getFileLocator();
        $esprima     = $this->getEsprima($fileLocator, 'esprima-2.0.0.js');

        $this->assertContains('esprima', $esprima->load());
    }

    /**
     * Test engine can be converted to string.
     *
     * @access public
     * @return void
     */
    public function testEngineCanBeCovertedToString()
    {
       $fileLocator = $this->getFileLocator();
        $esprima     = $this->getEsprima($fileLocator, 'esprima-2.0.0.js');

        $this->assertContains('esprima', (string) $esprima);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get esprima.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Validator\Esprima
     */
    protected function getEsprima(FileLocator $fileLocator, $file)
    {
        $esprima = new Esprima($fileLocator, $file);

        return $esprima;
    }

    /**
     * Get file locator.
     *
     * @access protected
     * @return \Symfony\Component\Config\FileLocator
     */
    protected function getFileLocator()
    {
        $fileLocator = new FileLocator(
            sprintf('%s/../../../Resources/validators/', __DIR__)
        );

        return $fileLocator;
    }
}
