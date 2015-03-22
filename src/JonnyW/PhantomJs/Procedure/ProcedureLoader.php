<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Procedure;

use Symfony\Component\Config\FileLocatorInterface;
use JonnyW\PhantomJs\Exception\NotExistsException;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureLoader implements ProcedureLoaderInterface
{
    /**
     * Procedure factory.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface
     * @access protected
     */
    protected $procedureFactory;

    /**
     * File locator.
     *
     * @var \Symfony\Component\Config\FileLocatorInterface
     * @access protected
     */
    protected $locator;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface $procedureFactory
     * @param \Symfony\Component\Config\FileLocatorInterface        $locator
     */
    public function __construct(ProcedureFactoryInterface $procedureFactory, FileLocatorInterface $locator)
    {
        $this->procedureFactory = $procedureFactory;
        $this->locator          = $locator;
    }

    /**
     * Load procedure instance by id.
     *
     * @access public
     * @param  string                                         $id
     * @return \JonnyW\PhantomJs\Procedure\ProcedureInterface
     */
    public function load($id)
    {
        $procedure = $this->procedureFactory->createProcedure();
        $procedure->setTemplate(
            $this->loadTemplate($id)
        );

        return $procedure;
    }

    /**
     * Load procedure template by id.
     *
     * @access public
     * @param  string $id
     * @param  string $extension (default: 'proc')
     * @return string
     */
    public function loadTemplate($id, $extension = 'proc')
    {
        $path = $this->locator->locate(sprintf('%s.%s', $id, $extension));

        return $this->loadFile($path);
    }

    /**
     * Load procedure file content.
     *
     * @access protected
     * @param  string                                         $file
     * @return string
     * @throws \InvalidArgumentException
     * @throws \JonnyW\PhantomJs\Exception\NotExistsException
     */
    protected function loadFile($file)
    {
        if (!stream_is_local($file)) {
            throw new \InvalidArgumentException(sprintf('Procedure file is not a local file: "%s"', $file));
        }

        if (!file_exists($file)) {
            throw new NotExistsException(sprintf('Procedure file does not exist: "%s"', $file));
        }

        return file_get_contents($file);
    }
}
