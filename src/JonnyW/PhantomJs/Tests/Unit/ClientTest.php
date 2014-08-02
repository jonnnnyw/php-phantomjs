<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit;

use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\Message\MessageFactoryInterface;
use JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface;

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
     * Test get instance returns instance
     * of client.
     *
     * @access public
     * @return void
     */
    public function testGetInstanceReturnsInstanceOfClient()
    {
        $this->assertInstanceOf('\JonnyW\PhantomJs\Client', Client::getInstance());
    }

    /**
     * Test get message factory returns instance
     * of message factory.
     *
     * @return void
     */
    public function testGetMessageFactoryReturnsInstanceOfMessageFactory()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Message\MessageFactoryInterface', $client->getMessageFactory());
    }

    /**
     * Test get procedure loader returns
     * instance of proecure loader.
     *
     * @return void
     */
    public function testGetProcedureLoaderReturnsInstanceOfProcedureLoader()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface', $client->getProcedureLoader());
    }

    /**
     * Test set phantom JS throws invalid
     * executable exception if phantom
     * JS path is invalid.
     *
     * @access public
     * @return void
     */
    public function testSetPhantomJsThrowsInvalidExecutableExceptionIfPhantomJsPathIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidExecutableException');

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setPhantomJs('/invalid/phantomjs/path');
    }

    /**
     * Test set phantom loader throws invalid
     * executable exception if phantom
     * loader path is invalid.
     *
     * @access public
     * @return void
     */
    public function testSetPhantomLoaderThrowsInvalidExecutableExceptionIfPhantomLoaderPathIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidExecutableException');

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setPhantomLoader('/invalid/phantomloader/path');
    }

    /**
     * Test get phantom JS returns default path
     * to phantom JS executable.
     *
     * @access public
     * @return void
     */
    public function testGetPhantomJsReturnsDefaultPathToPhantomJsExecutable()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $this->assertSame('bin/phantomjs', $client->getPhantomJs());
    }

    /**
     * Test get phantom loader returns default path
     * to phantom loader executable.
     *
     * @access public
     * @return void
     */
    public function testGetPhantomLoaderReturnsDefaultPathToPhantomLoaderExecutable()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $this->assertSame('bin/phantomloader', $client->getPhantomLoader());
    }

    /**
     * Test set log sets log info in
     * client.
     *
     * @access public
     * @return void
     */
    public function testSetLogSetsLogInfoInClient()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $log = 'Test log info';

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setLog($log);

        $this->assertSame($log, $client->getLog());
    }

    /**
     * Test clear log clears log
     * info in client.
     *
     * @access public
     * @return void
     */
    public function testClearLogsClearsLogInfoInClient()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $log = 'Test log info';

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setLog($log);
        $client->clearLog();

        $this->assertEmpty($client->getLog());
    }

    /**
     * Test add option adds option to
     * Phantom Js run options if option
     * is not set.
     *
     * @access public
     * @return void
     */
    public function testAddOptionAddsOptionToPhantomJsRunOptionsIfOptionIsNotSet()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $options = array(
            'option1',
            'option2'
        );

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setOptions($options);
        $client->addOption('option3');

        array_push($options, 'option3');

        $this->assertSame($options, $client->getOptions());
    }

    /**
     * Test add option does not add option
     * to Phantom Js run options if option
     * is set.
     *
     * @access public
     * @return void
     */
    public function testAddOptionDoesNotAddOptionToPhantomJsRunOptionsIfOptionIsSet()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $options = array(
            'option1',
            'option2'
        );

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setOptions($options);
        $client->addOption('option2');

        $this->assertSame($options, $client->getOptions());
    }

    /**
     * Test get command throws invalid executable
     * exception if PhantomJs is invalid.
     *
     * @access public
     * @return void
     */
    public function testGetCommandThrowsInvalidExecutableExceptionIfPhantomJsIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidExecutableException');

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $phantomJs = new \ReflectionProperty(get_class($client), 'phantomJs');
        $phantomJs->setAccessible(true);
        $phantomJs->setValue($client, 'invalid/path');

        $client->getCommand();
    }

    /**
     * Test get command throws invalid executable
     * exception if PhantomLoader is invalid..
     *
     * @access public
     * @return void
     */
    public function testGetCommandThrowsInvalidExecutableExceptionIfPhantomLoaderIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidExecutableException');

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $phantomLoader = new \ReflectionProperty(get_class($client), 'phantomLoader');
        $phantomLoader->setAccessible(true);
        $phantomLoader->setValue($client, 'invalid/path');

        $client->getCommand();
    }

    /**
     * Test get command contains PhantomKs executable.
     *
     * @access public
     * @return void
     */
    public function testGetCommandContainsPhantomJsExecutable()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $this->assertContains($client->getPhantomJs(), $client->getCommand());
    }

    /**
     * Test get command contains PhantomLoader executable.
     *
     * @access public
     * @return void
     */
    public function testGetCommandContainsPhantomLoaderExecutable()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $this->assertContains($client->getPhantomLoader(), $client->getCommand());
    }

    /**
     * Test get command sets debug flag if debug is
     * set to true.
     *
     * @access public
     * @return void
     */
    public function testGetCommandSetsDebugFlagIfDebugIsSetToTrue()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->debug(true);

        $this->assertContains('--debug=true', $client->getCommand());
    }

    /**
     * Test get command does not set debug flag if
     * debug is set to false.
     *
     * @access public
     * @return void
     */
    public function testGetCommandDoesNotSetDebugFlagIfDebugIsSetToFalse()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->debug(false);

        $this->assertNotContains('--debug=true', $client->getCommand());
    }

    /**
     * Test get command sets 1 option.
     *
     * @access public
     * @return void
     */
    public function testGetCommandSets1Option()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $option = '--local-storage-path=/some/path';

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->addOption($option);

        $this->assertContains($option, $client->getCommand());
    }

    /**
     * Test get command sets multiple options.
     *
     * @access public
     * @return void
     */
    public function testGetCommandSetsMultipleOptions()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $option1 = '--local-storage-path=/some/path';
        $option2 = '--local-storage-quota=5';
        $option3 = '--local-to-remote-url-access=true';

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->addOption($option1);
        $client->addOption($option2);
        $client->addOption($option3);

        $command = $client->getCommand();

        $this->assertContains($option1, $command);
        $this->assertContains($option2, $command);
        $this->assertContains($option3, $command);
    }

    /**
     * Test get command sets debug option if
     * additional options are set.
     *
     * @access public
     * @return void
     */
    public function testGetCommandSetsDebugOptionIfAdditionalOptionsAreSet()
    {
        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $option = '--local-storage-path=/some/path';

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->addOption($option);
        $client->debug(true);

        $command = $client->getCommand();

        $this->assertContains($option, $command);
        $this->assertContains('--debug=true', $command);
    }

    /**
     * Test set bin dir strips forward
     * slash from end.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirStripsForwardSlashFromEnd()
    {
        $binDir = '/path/to/bin/dir/';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setBinDir($binDir);

        $this->assertSame('/path/to/bin/dir', $client->getBinDir());
    }

    /**
     * Test set bin dir strips multiple forward
     * slashes from end.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirStripsMultipleForwardSlashesFromEnd()
    {
        $binDir = '/path/to/bin/dir//';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setBinDir($binDir);

        $this->assertSame('/path/to/bin/dir', $client->getBinDir());
    }

    /**
     * Test set bin dir strips backslash
     * from end.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirStripsBackslashFromEnd()
    {
        $binDir = '\path\to\bin\dir\\';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setBinDir($binDir);

        $this->assertSame('\path\to\bin\dir', $client->getBinDir());
    }

    /**
     * Test set bin dir strips multiple
     * backslashes from end.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirStripsMultipleBackslashesFromEnd()
    {
        $binDir = '\path\to\bin\dir\\\\';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setBinDir($binDir);

        $this->assertSame('\path\to\bin\dir', $client->getBinDir());
    }

    /**
     * Test set bin dir sets path of
     * PhantomJs executable.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirSetsPathOfPhantomJsExecutable()
    {
        $binDir = '/path/to/bin/dir';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setBinDir($binDir);

        $this->assertSame('/path/to/bin/dir/phantomjs', $client->getPhantomJs());
    }

    /**
     * Test set bin dir sets path of
     * Phantom loader executable.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirSetsPathOfPhantomLoaderExecutable()
    {
        $binDir = '/path/to/bin/dir';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);
        $client->setBinDir($binDir);

        $this->assertSame('/path/to/bin/dir/phantomloader', $client->getPhantomLoader());
    }

    /**
     * Test set bin dir does not set
     * path of PhantomJs executable if
     * custom PhantomJs executable path
     * is set.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirDoesNotSetPathToPhantomJsExecutableIfCustomPhantomJsPathIsSet()
    {
        $binDir  = '/path/to/bin/dir';
        $path    = '/path/to/phantomjs';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $phantomJs = new \ReflectionProperty(get_class($client), 'phantomJs');
        $phantomJs->setAccessible(true);
        $phantomJs->setValue($client, $path);

        $client->setBinDir($binDir);

        $this->assertSame($path, $client->getPhantomJs());
    }

    /**
     * Test set bin dir does not set
     * path of Phantom loader executable if
     * custom Phantom loader executable path
     * is set.
     *
     * @access public
     * @return void
     */
    public function testSetBinDirDoesNotSetPathToPhantomLoaderExecutableIfCustomPhantomLoaderPathIsSet()
    {
        $binDir  = '/path/to/bin/dir';
        $path    = '/path/to/phantomloader';

        $procedureLoader = $this->getProcedureLoader();
        $messageFactory  = $this->getMessageFactory();

        $client = $this->getClient($procedureLoader, $messageFactory);

        $phantomLoader = new \ReflectionProperty(get_class($client), 'phantomLoader');
        $phantomLoader->setAccessible(true);
        $phantomLoader->setValue($client, $path);

        $client->setBinDir($binDir);

        $this->assertSame($path, $client->getPhantomLoader());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get client instance
     *
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface $procedureLoader
     * @param  \JonnyW\PhantomJs\Message\MessageFactoryInterface    $messageFactory
     * @return \JonnyW\PhantomJs\Client
     */
    protected function getClient(ProcedureLoaderInterface $procedureLoader, MessageFactoryInterface $messageFactory)
    {
        $client = new Client($procedureLoader, $messageFactory);

        return $client;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get mock message factory instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Message\MessageFactoryInterface
     */
    protected function getMessageFactory()
    {
        $mockMessageFactory = $this->getMock('\JonnyW\PhantomJs\Message\MessageFactoryInterface');

        return $mockMessageFactory;
    }

    /**
     * Get mock procedure loader instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    protected function getProcedureLoader()
    {
        $mockProcedureLoader = $this->getMock('\JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface');

        return $mockProcedureLoader;
    }
}
