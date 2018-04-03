<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Cache;

use JonnyW\PhantomJs\StringUtils;
use JonnyW\PhantomJs\Exception\NotWritableException;
use JonnyW\PhantomJs\Exception\NotExistsException;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class FileCache implements CacheInterface
{
    /**
     * Default write directory
     *
     * @var string
     * @access protected
     */
    protected $directory;

    /**
     * Default write extension
     *
     * @var string
     * @access protected
     */
    protected $extension;

    /**
     * Internal constructor.
     *
     * @access public
     * @param string $directory
     * @param string $extension
     */
    public function __construct($directory, $extension)
    {
        $this->directory = rtrim($directory, DIRECTORY_SEPARATOR);
        $this->extension = $extension;
    }

    /**
     * Write data to storage.
     *
     * @access public
     * @param  string                                           $id
     * @param  string                                           $data
     * @return string
     * @throws \JonnyW\PhantomJs\Exception\NotWritableException
     */
    public function save($id, $data)
    {
        $file = $this->getFilename($id);

        if (!$this->isWritable($file)) {
            throw new NotWritableException(sprintf('File could not be written to system as target is not writable: %s', $file));
        }

        if ($this->writeData($file, $data) === false) {

            $this->delete($file);

            throw new NotWritableException(sprintf('Data could not be written to file on system. Please make sure that file is writeable: %s', $file));
        }

        return $file;
    }

    /**
     * Fetch data from file.
     *
     * @access public
     * @param  string                                         $id
     * @return mixed|void
     * @throws \JonnyW\PhantomJs\Exception\NotExistsException
     */
    public function fetch($id)
    {
        $file = $this->getFilename($id);

        if (!$this->exists($id)) {
            throw new NotExistsException(sprintf('Could not fetch data from file as file does not exist: %s', $file));
        }

        return $this->readData($file);
    }

    /**
     * Delete data from storage.
     *
     * @access public
     * @param  string $id
     * @return void
     */
    public function delete($id)
    {
        $files = glob($this->getFilename($id));

        if (count($files)) {
            array_map('unlink', $files);
        }
    }

    /**
     * Data exists in storage.
     *
     * @access public
     * @param  string  $id
     * @return boolean
     */
    public function exists($id)
    {
        return (bool) (file_exists($this->getFilename($id)));
    }

    /**
     * Is data writeable.
     *
     * @access protected
     * @param $file
     * @return boolean
     */
    protected function isWritable($file)
    {
        return (bool) ((file_exists($file) && is_writable($file)) || (!file_exists($file) && is_writable(dirname($file))));
    }

    /**
     * Write data to file.
     *
     * @access protected
     * @param  string  $file
     * @param  string  $data
     * @return boolean
     */
    protected function writeData($file, $data)
    {
        return file_put_contents($file, $data);
    }

    /**
     * Read data from file.
     *
     * @access protected
     * @param  string $file
     * @return mixed
     */
    protected function readData($file)
    {
        return file_get_contents($file);
    }

    /**
     * Get filename
     *
     * @access protected
     * @param  string                                           $id
     * @return string
     * @throws \JonnyW\PhantomJs\Exception\NotWritableException
     */
    protected function getFileName($id)
    {
        if (is_dir($id)) {
            return sprintf('%1$s/%2$s.%3$s', rtrim($id, DIRECTORY_SEPARATOR), StringUtils::random(20), $this->extension);
        }

        $dirName = dirname($id);

        if (!file_exists($id) && $dirName === '.') {
             return sprintf('%1$s/%2$s', $this->directory, $id);
        }

        if (!file_exists($id) && !is_writable($dirName)) {
            throw new NotWritableException(sprintf('File could not be written to system as target is not writable: %s', $id));
        }

        return $id;
    }
}
