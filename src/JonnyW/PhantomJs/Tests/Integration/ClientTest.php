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
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

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
        $client->setProcedure('test');
        $client->getProcedureLoader()->addLoader($procedureLoader);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

        $this->assertSame($content, $response->getContent());
    }

    /**
     * Test additional procedures can be loaded
     * through chain loader if procedures
     * contain comments
     *
     * @access public
     * @return void
     */
    public function testAdditionalProceduresCanBeLoadedThroughChainLoaderIfProceduresContainComments()
    {
        $content = 'TEST_PROCEDURE';

        $procedure = <<<EOF
    console.log(JSON.stringify({"content": "$content"}, undefined, 4));
    phantom.exit(1);
    var test = function () {
        // Test comment
        console.log('test');
    };
EOF;

        $this->writeProcedure($procedure);

        $procedureLoaderFactory = $this->getContainer()->get('procedure_loader_factory');
        $procedureLoader        = $procedureLoaderFactory->createProcedureLoader($this->directory);

        $client = $this->getClient();
        $client->setProcedure('test');
        $client->getProcedureLoader()->addLoader($procedureLoader);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);

        $this->assertSame($content, $response->getContent());
    }

    /**
     * Test syntax exception is thrown if request
     * procedure contains syntax error.
     *
     * @access public
     * @return void
     */
    public function testSyntaxExceptionIsThrownIfRequestProcedureContainsSyntaxError()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\SyntaxException');

        $content = 'TEST_PROCEDURE';

        $procedure = <<<EOF
    console.log(;
EOF;

        $this->writeProcedure($procedure);

        $procedureLoaderFactory = $this->getContainer()->get('procedure_loader_factory');
        $procedureLoader        = $procedureLoaderFactory->createProcedureLoader($this->directory);

        $client = $this->getClient();
        $client->setProcedure('test');
        $client->getProcedureLoader()->addLoader($procedureLoader);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $client->send($request, $response);
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
     * Test redirect URL is set in response
     * if request is redirected.
     *
     * @access public
     * @return void
     */
    public function testRedirectUrlIsSetInResponseIfRequestIsRedirected()
    {
        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('https://jigsaw.w3.org/HTTP/300/302.html');

        $client->send($request, $response);

        $this->assertNotEmpty($response->getRedirectUrl());
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
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);

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
        $request->setOutputFile($file);
        $request->setCaptureDimensions($width, $height);

        $client->send($request, $response);

        $imageInfo = getimagesize($file);

        $this->assertEquals($width, $imageInfo[0]);
        $this->assertEquals($height, $imageInfo[1]);
    }

    /**
     * Test PDF request saves pdf to
     * to local disk.
     *
     * @access public
     * @return void
     */
    public function testPdfRequestSavesPdfToLocalDisk()
    {
        $this->filename = 'test.pdf';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createPdfRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);

        $client->send($request, $response);

        $this->assertTrue(file_exists($file));
    }

    /**
     * Test PDF request saves file to
     * disk with correct paper size.
     *
     * @access public
     * @return void
     */
    public function testPdfRequestSavesFileToDiskWithCorrectPaperSize()
    {
        $this->filename = 'test.pdf';
        $file = ($this->directory . '/' . $this->filename);

        $width  = 20;
        $height = 30;

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createPdfRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);
        $request->setPaperSize(sprintf('%scm', $width), sprintf('%scm', $height));
        $request->setMargin('0cm');

        $client->send($request, $response);

        $pdf = \ZendPdf\PdfDocument::load($file);

        $pdfWidth  = round(($pdf->pages[0]->getWidth() * 0.0352777778));
        $pdfHeight = round(($pdf->pages[0]->getHeight()  * 0.0352777778));

        $this->assertEquals($width, $pdfWidth);
        $this->assertEquals($height, $pdfHeight);
    }

    /**
     * Test PDF request saves file to
     * disk with correct format size.
     *
     * @access public
     * @return void
     */
    public function testPdfRequestSavesFileToDiskWithCorrectFormatSize()
    {
        $this->filename = 'test.pdf';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createPdfRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);
        $request->setFormat('A4');
        $request->setMargin('0cm');

        $client->send($request, $response);

        $pdf = \ZendPdf\PdfDocument::load($file);

        $pdfWidth  = round(($pdf->pages[0]->getWidth() * 0.0352777778));
        $pdfHeight = round(($pdf->pages[0]->getHeight()  * 0.0352777778));

        $this->assertEquals(21, $pdfWidth);
        $this->assertEquals(30, $pdfHeight);
    }

    /**
     * Test PDF request saves file to
     * disk with correct orientation.
     *
     * @access public
     * @return void
     */
    public function testPdfRequestSavesFileToDiskWithCorrectOrientation()
    {
        $this->filename = 'test.pdf';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createPdfRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);
        $request->setFormat('A4');
        $request->setOrientation('landscape');
        $request->setMargin('0cm');

        $client->send($request, $response);

        $pdf = \ZendPdf\PdfDocument::load($file);

        $pdfWidth  = round(($pdf->pages[0]->getWidth() * 0.0352777778));
        $pdfHeight = round(($pdf->pages[0]->getHeight()  * 0.0352777778));

        $this->assertEquals(30, $pdfWidth);
        $this->assertEquals(21, $pdfHeight);
    }

    /**
     * Test can set repeating header
     * for PDF request
     *
     * @access public
     * @return void
     */
    public function testCanSetRepeatingHeaderForPDFRequest()
    {
        $this->filename = 'test.pdf';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createPdfRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);
        $request->setFormat('A4');
        $request->setOrientation('landscape');
        $request->setMargin('0cm');
        $request->setRepeatingHeader('<h1>Header <span style="float:right">%pageNum% / %pageTotal%</span></h1>', '2cm');
        $request->setRepeatingFooter('<footer>Footer <span style="float:right">%pageNum% / %pageTotal%</span></footer>', '2cm');

        $client->send($request, $response);

        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($file);

        $text = str_replace(' ', '', $pdf->getText());

        $this->assertContains('Header', $text);
    }

    /**
     * Test can set repeating footer
     * for PDF request
     *
     * @access public
     * @return void
     */
    public function testCanSetRepeatingFooterForPDFRequest()
    {
        $this->filename = 'test.pdf';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createPdfRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setOutputFile($file);
        $request->setFormat('A4');
        $request->setOrientation('landscape');
        $request->setMargin('0cm');
        $request->setRepeatingHeader('<h1>Header <span style="float:right">%pageNum% / %pageTotal%</span></h1>', '2cm');
        $request->setRepeatingFooter('<footer>Footer <span style="float:right">%pageNum% / %pageTotal%</span></footer>', '2cm');

        $client->send($request, $response);

        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile($file);

        $text = str_replace(' ', '', $pdf->getText());

        $this->assertContains('Footer', $text);
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

        $logs = explode("\\n", $client->getLog());

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

        $logs = explode("\\n", $client->getLog());

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

        $logs = explode("\\n", $client->getLog());

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

        $logs = explode("\\n", $client->getLog());

        $startIndex = $this->getLogEntryIndex($logs, 'Delaying page render for');
        $endIndex   = $this->getLogEntryIndex($logs, 'Rendering page after');

        $startTime = strtotime(substr($logs[$startIndex], 0 , 19));
        $endTime   = strtotime(substr($logs[$endIndex], 0 , 19));

        $this->assertSame(($startTime+$delay), $endTime);
    }

    /**
     * Test lazy request returns content after
     * all resources are loaded
     *
     * @access public
     * @return void
     */
    public function testLazyRequestReturnsResourcesAfterAllResourcesAreLoaded()
    {
        $client = $this->getClient();
        $client->isLazy();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-lazy.php');
        $request->setTimeout(5000);

        $client->send($request, $response);

        $this->assertContains('<p id="content">loaded</p>', $response->getContent());
    }

    /**
     * Test content is returned for lazy request
     * if timeout is reached before resource is
     * loaded
     *
     * @access public
     * @return void
     */
    public function testContentIsReturnedForLazyRequestIfTimeoutIsReachedBeforeResourceIsLoaded()
    {
        $client = $this->getClient();
        $client->isLazy();

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-lazy.php');
        $request->setTimeout(1000);

        $client->send($request, $response);

        $this->assertContains('<p id="content"></p>', $response->getContent());
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
        $client->getEngine()->debug(true);

        $request  = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-default.php');

        $client->send($request, $response);

        $this->assertContains('[DEBUG]', $client->getLog());
    }

    /**
     * Test test can set page
     * background color
     *
     * @access public
     * @return void
     */
    public function testCanSetPageBackgroundColor()
    {
        $this->filename = 'test.jpg';
        $file = ($this->directory . '/' . $this->filename);

        $client = $this->getClient();

        $request  = $client->getMessageFactory()->createCaptureRequest();
        $response = $client->getMessageFactory()->createResponse();

        $request->setMethod('GET');
        $request->setUrl('http://jonnyw.kiwi/tests/test-capture.php');
        $request->setBodyStyles(array('backgroundColor' => 'red'));
        $request->setOutputFile($file);

        $client->send($request, $response);

        $this->assertContains('body style="background-color: red;"', $response->getContent());
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
        $serviceContainer = ServiceContainer::getInstance();

        $client = new Client(
            $serviceContainer->get('engine'),
            $serviceContainer->get('procedure_loader'),
            $serviceContainer->get('procedure_compiler'),
            $serviceContainer->get('message_factory')
        );

        return $client;
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
