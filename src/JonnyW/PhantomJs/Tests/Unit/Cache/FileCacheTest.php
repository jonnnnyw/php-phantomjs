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
     * Test false is returned if file
     * does not exist.
     *
     * @access public
     * @return void
     */
    public function testFalseIsReturnedIfFileDoesNotExist()
    {
        $fileCache = $this->getFileCache($this->directory, 'txt');

        $this->assertFalse($fileCache->exists($this->filename, 'Test'));
    }

    /**
     * Test true is returned if file does exist.
     *
     * @access public
     * @return void
     */
    public function testTrueIsReturnedIfFileDoesExist()
    {
        touch($this->getFilename());

        $fileCache = $this->getFileCache($this->directory, 'txt');

        $this->assertTrue($fileCache->exists($this->filename));
    }

    /**
     * Test not writable exception is thrown if file
     * cannot be saved due to write permissions.
     *
     * @return void
     */
    public function testNotWritableExceptionIsThrownIfFileCannotBeSavedDueToWritePermissions()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $fileCache = $this->getFileCache('/This/Directory/Is/Not/Writable/', 'txt');
        $fileCache->save($this->filename, 'Test');
    }

    /**
     * Test test file location is returned
     * if file is successfully saved.
     *
     * @access public
     * @return void
     */
    public function testFileLocationIsReturnedIfFileIsSuccessfullySaved()
    {
        $fileCache  = $this->getFileCache($this->directory, 'txt');
        $file       = $fileCache->save($this->filename, 'Test');

        $this->assertInternalType('string', $file);
        $this->assertFileExists($file);
    }

    /**
     * Test file can be saved
     * with directory path
     *
     * @access public
     * @return void
     */
    public function testFileCanBeSavedWithDirectoryPath()
    {
        $fileCache  = $this->getFileCache('', 'txt');
        $file       = $fileCache->save($this->directory, 'Test');

        $this->assertSame(dirname($file), $this->directory);

        unlink($file);
    }

    /**
     * Test not writable exception is thrown
     * if directory path is not writable.
     *
     * @access public
     * @return void
     */
    public function testNotWritableExceptionIsThrownIfDirectoryPathIsNotWritable()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $fileCache  = $this->getFileCache($this->directory, 'txt');
        $file       = $fileCache->save('/This/Directory/Is/Not/Writable/', 'Test');
    }

    /**
     * Test file can be saved with absolute
     * path.
     *
     * @access public
     * @return void
     */
    public function testFileCanBeSavedWithAbsolutePath()
    {
        $test = sprintf('%1$s/%2$s', $this->directory, 'new-file.txt');

        $fileCache = $this->getFileCache('', 'txt');
        $file      = $fileCache->save($test, 'Test');

        $this->assertSame($test, $file);

        unlink($file);
    }

    /**
     * Test not exists exception is thrown when
     * fetching data that doesn't exsit
     *
     * @access public
     * @return void
     */
    public function testNotExistsExceptionIsThrownIfWhenFetchingDataThatDoesntExist()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotExistsException');

        $fileCache = $this->getFileCache('', 'txt');

        $this->assertFalse($fileCache->fetch($this->filename));
    }

    /**
     * Test data can be fetched from cache.
     *
     * @access public
     * @return void
     */
    public function testDataCanBeFetchedFromCache()
    {
        $test = 'Test';

        $fileCache = $this->getFileCache($this->directory, 'txt');
        $fileCache->save($this->filename, $test);

        $content = $fileCache->fetch($this->filename);

        $this->assertSame($test, $content);
    }

    /**
     * Test data can be deleted from cache.
     *
     * @access public
     * @return void
     */
    public function testDataCanBeDeletedFromCache()
    {
        $fileCache = $this->getFileCache($this->directory, 'txt');

        $file = $fileCache->save($this->filename, 'Test');

        $fileCache->delete($this->filename);

        $this->assertFileNotExists($file);
    }

    /**
     * Test data can be deleted from
     * cache using wildcard.
     *.
     *
     * @access public
     * @return void
     */
    public function testDataCanBeDeletedFromCacheUsingWildcard()
    {
        $fileCache = $this->getFileCache($this->directory, 'txt');

        $file1 = $fileCache->save('test_file_1', 'Test1');
        $file2 = $fileCache->save('test_file_2', 'Test2');

        $fileCache->delete('test_file_*');

        $this->assertFileNotExists($file1);
        $this->assertFileNotExists($file2);
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
