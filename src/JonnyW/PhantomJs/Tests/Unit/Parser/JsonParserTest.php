<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Parser;

use JonnyW\PhantomJs\Parser\JsonParser;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class JsonParserTest extends \PHPUnit_Framework_TestCase
{

/*****************/
/***** TESTS *****/
/*****************/

    /**
     * Test parse returns array if data
     * is null.
     *
     * @access public
     * @return void
     */
    public function testParseReturnsArrayIfDataIsNull()
    {
        $data = null;

        $jsonParser = $this->getJsonParser();

        $this->assertInternalType('array', $jsonParser->parse($data));
    }

    /**
     * Test parse returns array if data
     * is not a string.
     *
     * @access public
     * @return void
     */
    public function testParseReturnsArrayIfDataIsNotAString()
    {
        $data = new \stdClass();

        $jsonParser = $this->getJsonParser();

        $this->assertInternalType('array', $jsonParser->parse($data));
    }

    /**
     * Test parse returns array if data
     * is invalid JSON format.
     *
     * @access public
     * @return void
     */
    public function testParseReturnsArrayIfDataIsInvalidJsonFormat()
    {
        $data = 'Invalid JSON format';

        $jsonParser = $this->getJsonParser();

        $this->assertInternalType('array', $jsonParser->parse($data));
    }

    /**
     * Test parse returns array if data
     * is broken json format.
     *
     * @access public
     * @return void
     */
    public function testParseReturnsArrayIfDataIsBrokenJsonFormat()
    {
        $data = '{data: Unquoted string}';

        $jsonParser = $this->getJsonParser();

        $this->assertInternalType('array', $jsonParser->parse($data));
    }

    /**
     * Test parse returns array if data
     * is valid JSON object.
     *
     * @access public
     * @return void
     */
    public function testParseReturnsArrayIfDataIsValidJsonObject()
    {
        $data = '{"data": "Test data"}';

        $jsonParser = $this->getJsonParser();

        $this->assertInternalType('array', $jsonParser->parse($data));
    }

    /**
     * Test parse returns array if data
     * is valid JSON array.
     *
     * @access public
     * @return void
     */
    public function testParseReturnsArrayIfDataIsValidJsonArray()
    {
        $data = '["Test data"]';

        $jsonParser = $this->getJsonParser();

        $this->assertInternalType('array', $jsonParser->parse($data));
    }

    /**
     * Test parse successfully parses data
     * if data is valid JSON object.
     *
     * @access public
     * @return void
     */
    public function testParseSuccessfullyParsesDataIfDataIsValidJsonObject()
    {
        $data = '{"data": "Test data"}';

        $jsonParser = $this->getJsonParser();
        $parsedData = $jsonParser->parse($data);

        $expectedData = array(
            'data' => 'Test data'
        );

        $this->assertSame($parsedData, $expectedData);
    }

    /**
     * Test parse successfully parses data
     * if data is valid JSON array.
     *
     * @access public
     * @return void
     */
    public function testParseSuccessfullyParsesDataIfDataIsValidJsonArray()
    {
        $data = '["Test data"]';

        $jsonParser = $this->getJsonParser();
        $parsedData = $jsonParser->parse($data);

        $expectedData = array(
            'Test data'
        );

        $this->assertSame($parsedData, $expectedData);
    }

    /**
     * Test parse successfully parses
     * multidimensional data if data is
     * valid JSON format.
     *
     * @access public
     * @return void
     */
    public function testParseSuccessfullyParsesMultidimensionalDataIfDataIsValidJsonFormat()
    {
         $data = '{
            "data": {
                "data": { "data": "Test data" },
                "more_data": "More test data"
            }
         }';

        $jsonParser = $this->getJsonParser();
        $parsedData = $jsonParser->parse($data);

        $expectedData = array(
            'data' => array(
                'data'      => array( 'data' => 'Test data' ),
                'more_data' => 'More test data'
            )
        );

        $this->assertSame($parsedData, $expectedData);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get JSON parser instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Parser\JsonParser
     */
    protected function getJsonParser()
    {
        $jsonParser = new JsonParser();

        return $jsonParser;
    }
}
