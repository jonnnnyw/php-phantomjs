<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Procedure;

use JonnyW\PhantomJs\Procedure\Output;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class OutputTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test data storage.
     *
     * @access public
     * @return void
     */
    public function testDataStorage()
    {
        $output = $this->getOutput();
        $output->set('test', 'Test value');

        $this->assertSame('Test value', $output->get('test'));
    }

    /**
     * Test can import data.
     *
     * @access public
     * @return void
     */
    public function testCanImportData()
    {
        $data = array(
          'test'  => 'Test value',
          'test2' => 'Test value 2'
        );

        $output = $this->getOutput();
        $output->import($data);

        $this->assertSame('Test value', $output->get('test'));
    }

    /**
     * Test can log data.
     *
     * @access public
     * @return void
     */
    public function testCanLogData()
    {
        $output = $this->getOutput();
        $output->log('Test log');

        $this->assertContains('Test log', $output->getLogs());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get output.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\Output
     */
    protected function getOutput()
    {
        $output = new Output();

        return $output;
    }
}
