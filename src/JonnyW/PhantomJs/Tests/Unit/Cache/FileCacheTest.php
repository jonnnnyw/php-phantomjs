<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Cache;

use JonnyW\PhantomJs\Cache\FileCache;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class FileCacheTest extends \PHPUnit_Framework_TestCase
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
     * Test exists returns false if
     * file does not exist.
     *
     * @access public
     * @return void
     */
    public function testExistsReturnsFalesIfFileDoesNotExist()
    {
        $fileCache = $this->getFileCache($this->directory, 'txt');

        $this->assertFalse($fileCache->exists($this->filename, 'Test'));
    }

    /**
     * Test exists returns true if
     * file does exist.
     *
     * @access public
     * @return void
     */
    public function testExistsReturnsTrueIfFileDoesExist()
    {
        touch($this->getFilename());

        $fileCache = $this->getFileCache($this->directory, 'txt');

        $this->assertTrue($fileCache->exists($this->filename));
    }

    /**
     * Test save throws not writable
     * exception if file is not writable
     *
     * @return void
     */
    public function testSaveThrowsNotWritableExceptionIfFileIsNotWritable()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $fileCache = $this->getFileCache('/This/Directory/Is/Not/Writable/', 'txt');
        $fileCache->save($this->filename, 'Test');
    }

    /**
     * Test save throws not writable
     * exception if write data returns
     * false
     *
     * @access public
     * @return void
     */
    public function testSaveThrowsNotWritableExceptionIfWriteFileReturnsFalse()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $fileCache = $this->getMockFileCache(array('writeData'), $this->directory, 'txt');
        $fileCache->method('writeData')
            ->will($this->returnValue(false));

        $fileCache->save($this->filename, 'Test');
    }

    /**
     * Test save returns file location if
     * file is successfully saved.
     *
     * @access public
     * @return void
     */
    public function testSaveReturnsFileLocationIfFileIsSuccessfullySaved()
    {
        $fileCache  = $this->getFileCache($this->directory, 'txt');
        $file       = $fileCache->save($this->filename, 'Test');

        $this->assertInternalType('string', $file);
        $this->assertFileExists($file);
    }

    /**
     * Test save with directory path
     * saves file to directory
     *
     * @access public
     * @return void
     */
    public function testSaveWithDirectoryPathSavesFileToDirectory()
    {
        $fileCache  = $this->getFileCache('', 'txt');
        $file       = $fileCache->save($this->directory, 'Test');

        $this->assertSame(dirname($file), $this->directory);

        unlink($file);
    }

    /**
     * Test save with directory path
     * throws not writable exception
     * if path is not writable
     *
     * @access public
     * @return void
     */
    public function testSaveWithDirectoryPathThrowsNotWritableExceptionIfPathIsNotWritable()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $fileCache  = $this->getFileCache($this->directory, 'txt');
        $file       = $fileCache->save('/This/Directory/Is/Not/Writable/', 'Test');
    }

    /**
     * Test save with absolute filename
     * saves file.
     *
     * @access public
     * @return void
     */
    public function testSaveWithAbsoluteFilenameSavesFile()
    {
        $test = sprintf('%1$s/%2$s', $this->directory, 'new-file.txt');

        $fileCache = $this->getFileCache('', 'txt');
        $file      = $fileCache->save($test, 'Test');

        $this->assertSame($test, $file);

        unlink($file);
    }

    /**
     * Test fetch data returns not exists
     * exception if file does not exist.
     *
     * @access public
     * @return void
     */
    public function testFetchDataThrowsNotExistsExceptionIfFileDoesNotExist()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotExistsException');

        $fileCache = $this->getFileCache('', 'txt');

        $this->assertFalse($fileCache->fetch($this->filename));
    }

    /**
     * Test fetch data returns data if
     * file exists..
     *
     * @access public
     * @return void
     */
    public function testFetchDataReturnsDataIfFileExists()
    {
        $test = 'Test';

        $fileCache = $this->getFileCache($this->directory, 'txt');
        $fileCache->save($this->filename, $test);

        $content = $fileCache->fetch($this->filename);

        $this->assertSame($test, $content);
    }

    /**
     * Test delete removes file.
     *
     * @access public
     * @return void
     */
    public function testDeleteRemovesFile()
    {
        $fileCache = $this->getFileCache($this->directory, 'txt');

        $file = $fileCache->save($this->filename, 'Test');

        $fileCache->delete($this->filename);

        $this->assertFileNotExists($file);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get file write instance
     *
     * @param  string                            $directory
     * @param  string                            $extension
     * @return \JonnyW\PhantomJs\Cache\FileCache
     */
    protected function getFileCache($directory, $extension)
    {
        $fileCache = new FileCache($directory, $extension);

        return $fileCache;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get mock file cache.
     *
     * @access protected
     * @param  array                             $methods
     * @param  string                            $directory
     * @param  string                            $extension
     * @return \JonnyW\PhantomJs\Cache\FileCache
     */
    protected function getMockFileCache(array $methods, $directory, $extension)
    {
        $mockFileCache = $this->getMock('\JonnyW\PhantomJs\Cache\FileCache', $methods, array($directory, $extension));

        return $mockFileCache;
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
        $this->filename  = 'test.txt';
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
}
