<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit;

use JonnyW\PhantomJs\Engine;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class EngineTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test invalid executable exception is thrown
     * if phantom JS path is invalid.
     *
     * @access public
     * @return void
     */
    public function testInvalidExecutableExceptionIsThrownIfPhantomJSPathIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidExecutableException');

        $engine = $this->getEngine();
        $engine->setPath('/invalid/phantomjs/path');
    }

    /**
     * Test default phantom JS path is returned
     * if no custom path is set.
     *
     * @access public
     * @return void
     */
    public function testDefaultPhantomJSPathIsReturnedIfNoCustomPathIsSet()
    {
        $engine = $this->getEngine();

        $this->assertSame('bin/phantomjs', $engine->getPath());
    }

    /**
     * Test can log data.
     *
     * @access public
     * @return void
     */
    public function testCanLogData()
    {
        $log = 'Test log info';

        $engine = $this->getEngine();
        $engine->log($log);

        $this->assertSame($log, $engine->getLog());
    }

    /**
     * Test can clear log.
     *
     * @access public
     * @return void
     */
    public function testCanClearLog()
    {
        $log = 'Test log info';

        $engine = $this->getEngine();
        $engine->log($log);
        $engine->clearLog();

        $this->assertEmpty($engine->getLog());
    }

    /**
     * Test can add run option.
     *
     * @access public
     * @return void
     */
    public function testCanAddRunOption()
    {
        $options = array(
            'option1',
            'option2'
        );

        $engine = $this->getEngine();
        $engine->setOptions($options);
        $engine->addOption('option3');

        array_push($options, 'option3');

        $this->assertSame($options, $engine->getOptions());
    }

    /**
     * Test invalid executable exception is thrown when
     * building command if path to phantom JS is valid.
     *
     * @access public
     * @return void
     */
    public function testInvalidExecutableExceptionIsThrownWhenBuildingCommandIfPathToPhantomJSIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidExecutableException');

        $engine = $this->getEngine();

        $phantomJs = new \ReflectionProperty(get_class($engine), 'path');
        $phantomJs->setAccessible(true);
        $phantomJs->setValue($engine, 'invalid/path');

        $engine->getCommand();
    }

    /**
     * Test command contains phantom JS executable
     *
     * @access public
     * @return void
     */
    public function testCommandContainsPhantomJSExecutable()
    {
        $engine = $this->getEngine();

        $this->assertContains($engine->getPath(), $engine->getCommand());
    }

    /**
     * Test debug flag can be set.
     *
     * @access public
     * @return void
     */
    public function testDebugFlagCanBeSet()
    {
        $engine = $this->getEngine();
        $engine->debug(true);

        $this->assertContains('--debug=true', $engine->getCommand());
    }

    /**
     * Test debug flag is not set if
     * debugging is not enabled.
     *
     * @access public
     * @return void
     */
    public function testDebugFlagIsNotSetIfDebuggingIsNotEnabled()
    {
        $engine = $this->getEngine();
        $engine->debug(false);

        $this->assertNotContains('--debug=true', $engine->getCommand());
    }

    /**
     * Test disk cache flag can be set.
     *
     * @access public
     * @return void
     */
    public function testDiskCacheFlagCanBeSet()
    {
        $engine = $this->getEngine();
        $engine->cache(true);

        $this->assertContains('--disk-cache=true', $engine->getCommand());
    }

    /**
     * Test disk cache flag is not set if
     * caching is not enabled.
     *
     * @access public
     * @return void
     */
    public function testDiskCacheFlagIsNotSetIfCachingIsNotEnabled()
    {
        $engine = $this->getEngine();
        $engine->cache(false);

        $this->assertNotContains('--disk-cache=true', $engine->getCommand());
    }

    /**
     * Test command contains run options.
     *
     * @access public
     * @return void
     */
    public function testCommandContainsRunOptions()
    {
        $option1 = '--local-storage-path=/some/path';
        $option2 = '--local-storage-quota=5';
        $option3 = '--local-to-remote-url-access=true';

        $engine = $this->getEngine();
        $engine->addOption($option1);
        $engine->addOption($option2);
        $engine->addOption($option3);

        $command = $engine->getCommand();

        $this->assertContains($option1, $command);
        $this->assertContains($option2, $command);
        $this->assertContains($option3, $command);
    }

    /**
     * Test debug flag is set if runs options
     * are also set.
     *
     * @access public
     * @return void
     */
    public function testDebugFlagIsSetIfRunOptionsAreAlsoSet()
    {
        $option = '--local-storage-path=/some/path';

        $engine = $this->getEngine();
        $engine->addOption($option);
        $engine->debug(true);

        $command = $engine->getCommand();

        $this->assertContains($option, $command);
        $this->assertContains('--debug=true', $command);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get client instance
     *
     * @return \JonnyW\PhantomJs\Engine
     */
    protected function getEngine()
    {
        $engine = new Engine();

        return $engine;
    }
}
