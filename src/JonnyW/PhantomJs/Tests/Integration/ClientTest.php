<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Integration;

use JonnyW\PhantomJs\Test\TestCase;
use JonnyW\PhantomJs\Client;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ClientTest extends TestCase
{
    /**
     * Test filename
     *
     * @var string
     * @access protected
     */
    protected $filename;

    /**
     * Test directory
     *
     * @var string
     * @access protected
     */
    protected $directory;

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test additional procedures can be loaded
     * through chain loader.
     *
     * @access public
     * @return void
     */
    public function testAdditionalProceduresCanBeLoadedThroughChainLoader()
    {
        $content = 'TEST_PROCEDURE';

        $procedure = <<<EOF
    console.log(JSON.stringify({"content": "$content"}, undefined, 4));
    phantom.exit(1);
EOF;

        $this->writeProcedure($procedure);

        $procedureLoaderFactory = $this->getContainer()->get('procedure_loader_factory');
        $procedureLoader        = $procedureLoaderFactory->createProcedureLoader($this->directory);

        $client = $this->getClient();
        $client->getProcedureLoader()->addLoader($procedureLoader);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setType('test');

        $client->send($request, $response);

        $this->assertSame($content, $response->getContent());
    }

    /**
     * Test request returns a status code of zero
     * if a procedure parse exception is encountered.
     *
     * @access public
     * @return void
     */
    public function testRequestReturnsAStatusCodeOfZeroIfAProcedureParseExceptionIsEncountered()
    {
        $content = 'TEST_PROCEDURE';

        $procedure = <<<EOF
    console.log(;
EOF;

        $this->writeProcedure($procedure);

        $procedureLoaderFactory = $this->getContainer()->get('procedure_loader_factory');
        $procedureLoader        = $procedureLoaderFactory->createProcedureLoader($this->directory);

        $client = $this->getClient();
        $client->getProcedureLoader()->addLoader($procedureLoader);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setType('test');

        $client->send($request, $response);

        $this->assertEquals(0, $response->getStatus());
    }

    /**
     * Test client contains parse error in log if
     * a parse exception is encountered.
     *
     * @access public
     * @return void
     */
    public function testClientContainsParseErrorInLogIfAParseExceptionIsEncountered()
    {
        $content = 'TEST_PROCEDURE';

        $procedure = <<<EOF
    console.log(;
EOF;

        $this->writeProcedure($procedure);

        $procedureLoaderFactory = $this->getContainer()->get('procedure_loader_factory');
        $procedureLoader        = $procedureLoaderFactory->createProcedureLoader($this->directory);

        $client = $this->getClient();
        $client->getProcedureLoader()->addLoader($procedureLoader);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setType('test');

        $client->send($request, $response);

        $this->assertContains('SyntaxError: Parse error', $client->getLog());
    }

    /**
     * Test response contains 200 status code if page
     * is successfully loaded.
     *
     * @access public
     * @return void
     */
    public function testResponseContains200StatusCodeIfPageIsSuccessfullyLoaded()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');

        $client->send($request, $response);

        $this->assertEquals(200, $response->getStatus());
    }

    /**
     * Test response contains 200 status code if
     * request URL contains reserved characters.
     *
     * @access public
     * @return void
     */
    public function testResponseContains200StatusCodeIfRequestUrlContainsReservedCharacters()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');
        $request->setRequestData(array(
            'test1' => 'http://test.com',
            'test2' => 'A string with an \' ) / # some other invalid [ characters.'
        ));

        $client->send($request, $response);

        $this->assertEquals(200, $response->getStatus());
    }

    /**
     * Test response contains valid body if page is
     * successfully loaded.
     *
     * @access public
     * @return void
     */
    public function testResponseContainsValidBodyIfPageIsSuccessfullyLoaded()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');

        $client->send($request, $response);

        $this->assertContains('PHANTOMJS_DEFAULT_TEST', $response->getContent());
    }

    /**
     * Test response contains console error if a
     * javascript error exists on the page.
     *
     * @access public
     * @return void
     */
    public function testResponseContainsConsoleErrorIfAJavascriptErrorExistsOnThePage()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-console-error.php');

        $client->send($request, $response);

        $console = $response->getConsole();

        $this->assertCount(1, $console);
        $this->assertContains('ReferenceError: Can\'t find variable: invalid', $console[0]['message']);
    }

    /**
     * Test response contains console trace if a
     * javascript error exists on the page.
     *
     * @access public
     * @return void
     */
    public function testResponseContainsConsoleTraceIfAJavascriptErrorExistsOnThePage()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-console-error.php');

        $client->send($request, $response);

        $console = $response->getConsole();

        $this->assertCount(1, $console[0]['trace']);
    }

    /**
     * Test response contains headers.
     *
     * @access public
     * @return void
     */
    public function testResponseContainsHeaders()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-console-error.php');

        $client->send($request, $response);

        $this->assertNotEmpty($response->getHeaders());
    }

    /**
     * Test POST request sends request data.
     *
     * @access public
     * @return void
     */
    public function testPostRequestSendsRequestData()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('POST');
        $request->setUrl('http://jonnyw.kiwi/tests/test-post.php');
        $request->setRequestData(array(
            'test1' => 'http://test.com',
            'test2' => 'A string with an \' ) / # some other invalid [ characters.'
        ));

        $client->send($request, $response);

        $this->assertContains(sprintf('<li>test1=%s</li>', 'http://test.com'), $response->getContent());
        $this->assertContains(sprintf('<li>test2=%s</li>', 'A string with an \' ) / # some other invalid [ characters.'), $response->getContent());
    }

    /**
     * Test capture request saves file to
     * to local disk.
     *
     * @access public
     * @return void
     */
    public function testCaptureRequestSavesFileToLocalDisk()
    {
        $this->filename = 'test.jpg';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-console-error.php');
        $request->setCaptureFile($file);

        $client->send($request, $response);

        $this->assertTrue(file_exists($file));
    }

    /**
     * Test capture request saves file to
     * disk with correct capture dimensions.
     *
     * @access public
     * @return void
     */
    public function testCaptureRequestSavesFileToDiskWithCorrectCaptureDimensions()
    {
        $this->filename = 'test.jpg';
        $file = ($this->directory . '/' . $this->filename);

        $width  = 200;
        $height = 400;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setCaptureFile($file);
        $request->setCaptureDimensions($width, $height);

        $client->send($request, $response);

        $imageInfo = getimagesize($file);

        $this->assertEquals($width, $imageInfo[0]);
        $this->assertEquals($height, $imageInfo[1]);
    }

    /**
     * Test set viewport size sets
     * size of viewport in default
     * request.
     *
     * @access public
     * @return void
     */
    public function testSetViewportSizeSetsSizeOfViewportInDefaultRequest()
    {
        $width  = 100;
        $height = 200;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');
        $request->setViewportsize($width, $height);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Set viewport size ~ width: 100 height: 200');

        $this->assertTrue(($startIndex !== false));
    }

    /**
     * Test set viewport size sets
     * size of viewport in capture
     * request.
     *
     * @access public
     * @return void
     */
    public function testSetViewportSizeSetsSizeOfViewportInCaptureRequest()
    {
        $width  = 100;
        $height = 200;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');
        $request->setViewportsize($width, $height);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Set viewport size ~ width: 100 height: 200');

        $this->assertTrue(($startIndex !== false));
    }

    /**
     * Test delay logs start time
     * in client for default request.
     *
     * @access public
     * @return void
     */
    public function testDelayLogsStartTimeInClientForDefaultRequest()
    {
        $delay = 1;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');
        $request->setDelay($delay);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Delaying page render for');

        $this->assertTrue(($startIndex !== false));
    }

    /**
     * Test delay logs end time
     * in client for default request.
     *
     * @access public
     * @return void
     */
    public function testDelayLogsEndTimeInClientForDefaultRequest()
    {
        $delay = 1;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');
        $request->setDelay($delay);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $endIndex = $this->getLogEntryIndex($logs, 'Rendering page after');

        $this->assertTrue(($endIndex !== false));
    }

    /**
     * Test delay delays page render for
     * specified time for default request.
     *
     * @access public
     * @return void
     */
    public function testDelayDelaysPageRenderForSpecifiedTimeForDefaultRequest()
    {
        $delay = 1;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');
        $request->setDelay($delay);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Delaying page render for');
        $endIndex   = $this->getLogEntryIndex($logs, 'Rendering page after');

        $startTime = strtotime(substr($logs[$startIndex], 0 , 19));
        $endTime   = strtotime(substr($logs[$endIndex], 0 , 19));

        $this->assertSame(($startTime+$delay), $endTime);
    }

    /**
     * Test delay logs start time
     * in client for capture request.
     *
     * @access public
     * @return void
     */
    public function testDelayLogsStartTimeInClientForCaptureRequest()
    {
        $delay = 1;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setDelay($delay);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Delaying page render for');

        $this->assertTrue(($startIndex !== false));
    }

    /**
     * Test delay logs end time
     * in client for capture request.
     *
     * @access public
     * @return void
     */
    public function testDelayLogsEndTimeInClientForCaptureRequest()
    {
        $delay = 1;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setDelay($delay);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $endIndex = $this->getLogEntryIndex($logs, 'Rendering page after');

        $this->assertTrue(($endIndex !== false));
    }

    /**
     * Test delay delays page render for
     * specified time for capture request.
     *
     * @access public
     * @return void
     */
    public function testDelayDelaysPageRenderForSpecifiedTimeForCaptureRequest()
    {
        $delay = 1;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setDelay($delay);

        $client->send($request, $response);

        $logs = explode("\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Delaying page render for');
        $endIndex   = $this->getLogEntryIndex($logs, 'Rendering page after');

        $startTime = strtotime(substr($logs[$startIndex], 0 , 19));
        $endTime   = strtotime(substr($logs[$endIndex], 0 , 19));

        $this->assertSame(($startTime+$delay), $endTime);
    }

    /**
     * Test debug logs debug info to
     * client log.
     *
     * @access public
     * @return void
     */
    public function testDebugLogsDebugInfoToClientLog()
    {
        $client = $this->getClient();
        $client->debug(true);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');

        $client->send($request, $response);

        $this->assertContains('[DEBUG]', $client->getLog());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get client instance.
     *
     * @return \JonnyW\PhantomJs\Client
     */
    protected function getClient()
    {
        return Client::getInstance();
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++ UTILITIES ++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Set up test environment.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        $this->filename  = 'test.proc';
        $this->directory = sys_get_temp_dir();

        if (!is_writable($this->directory)) {
            throw new \RuntimeException(sprintf('Test directory must be writable: %s', $this->directory));
        }
    }

    /**
     * Tear down test environment.
     *
     * @access public
     * @return void
     */
    public function tearDown()
    {
        $filename = $this->getFilename();

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * Get test filename.
     *
     * @access public
     * @return string
     */
    public function getFilename()
    {
        return sprintf('%1$s/%2$s', $this->directory, $this->filename);
    }

    /**
     * Write procedure body to file.
     *
     * @access public
     * @param  string $data
     * @return string
     */
    public function writeProcedure($procedure)
    {
        $filename = $this->getFilename();

        file_put_contents($filename, $procedure);

        return $filename;
    }

    /**
     * Get log entry index.
     *
     * @access public
     * @param  array     $logs
     * @param  string    $search
     * @return int|false
     */
    public function getLogEntryIndex(array $logs, $search)
    {
        foreach ($logs as $index => $log) {

            $pos = stripos($log, $search);

            if ($pos !== false) {
                return $index;
            }
        }

        return false;
    }
}
