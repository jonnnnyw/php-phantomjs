<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit;

use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\Http\MessageFactoryInterface;
use JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface;
use JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test can get client through
     * factory method.
     *
     * @access public
     * @return void
     */
    public function testCanGetClientThroughFactoryMethod()
    {
        $this->assertInstanceOf('\JonnyW\PhantomJs\Client', Client::getInstance());
    }

    /**
     * Test can get message factory
     *
     * @return void
     */
    public function testCanGetMessageFactory()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Http\MessageFactoryInterface', $client->getMessageFactory());
    }

    /**
     * Test can get procedure loader.
     *
     * @return void
     */
    public function testCanGetProcedureLoader()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface', $client->getProcedureLoader());
    }

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

        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->setPhantomJs('/invalid/phantomjs/path');
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
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);

        $this->assertSame('bin/phantomjs', $client->getPhantomJs());
    }

    /**
     * Test can log data.
     *
     * @access public
     * @return void
     */
    public function testCanLogData()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $log = 'Test log info';

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->log($log);

        $this->assertSame($log, $client->getLog());
    }

    /**
     * Test can clear log.
     *
     * @access public
     * @return void
     */
    public function testCanClearLog()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $log = 'Test log info';

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->log($log);
        $client->clearLog();

        $this->assertEmpty($client->getLog());
    }

    /**
     * Test can add run option.
     *
     * @access public
     * @return void
     */
    public function testCanAddRunOption()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $options = array(
            'option1',
            'option2'
        );

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->setOptions($options);
        $client->addOption('option3');

        array_push($options, 'option3');

        $this->assertSame($options, $client->getOptions());
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

        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);

        $phantomJs = new \ReflectionProperty(get_class($client), 'phantomJs');
        $phantomJs->setAccessible(true);
        $phantomJs->setValue($client, 'invalid/path');

        $client->getCommand();
    }

    /**
     * Test command contains phantom JS executable
     *
     * @access public
     * @return void
     */
    public function testCommandContainsPhantomJSExecutable()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);

        $this->assertContains($client->getPhantomJs(), $client->getCommand());
    }

    /**
     * Test debug flag can be set.
     *
     * @access public
     * @return void
     */
    public function testDebugFlagCanBeSet()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->debug(true);

        $this->assertContains('--debug=true', $client->getCommand());
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
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->debug(false);

        $this->assertNotContains('--debug=true', $client->getCommand());
    }

    /**
     * Test command contains run options.
     *
     * @access public
     * @return void
     */
    public function testCommandContainsRunOptions()
    {
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $option1 = '--local-storage-path=/some/path';
        $option2 = '--local-storage-quota=5';
        $option3 = '--local-to-remote-url-access=true';

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->addOption($option1);
        $client->addOption($option2);
        $client->addOption($option3);

        $command = $client->getCommand();

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
        $procedureLoader    = $this->getProcedureLoader();
        $procedureValidator = $this->getProcedureValidator();
        $messageFactory     = $this->getMessageFactory();

        $option = '--local-storage-path=/some/path';

        $client = $this->getClient($procedureLoader, $procedureValidator, $messageFactory);
        $client->addOption($option);
        $client->debug(true);

        $command = $client->getCommand();

        $this->assertContains($option, $command);
        $this->assertContains('--debug=true', $command);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get client instance
     *
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface    $procedureLoader
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface $procedureValidator
     * @param  \JonnyW\PhantomJs\Http\MessageFactoryInterface          $messageFactory
     * @return \JonnyW\PhantomJs\Client
     */
    protected function getClient(ProcedureLoaderInterface $procedureLoader, ProcedureValidatorInterface $procedureValidator, MessageFactoryInterface $messageFactory)
    {
        $client = new Client($procedureLoader, $procedureValidator, $messageFactory);

        return $client;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get message factory
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Http\MessageFactoryInterface
     */
    protected function getMessageFactory()
    {
        $messageFactory = $this->getMock('\JonnyW\PhantomJs\Http\MessageFactoryInterface');

        return $messageFactory;
    }

    /**
     * Get procedure loader.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    protected function getProcedureLoader()
    {
        $procedureLoader = $this->getMock('\JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface');

        return $procedureLoader;
    }

    /**
     * Get procedure validator.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    protected function getProcedureValidator()
    {
        $procedureValidator = $this->getMock('\JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface');

        return $procedureValidator;
    }
}
