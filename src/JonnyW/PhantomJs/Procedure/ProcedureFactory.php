<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Procedure;

use \JonnyW\PhantomJs\Cache\CacheInterface;
use \JonnyW\PhantomJs\Parser\ParserInterface;
use \JonnyW\PhantomJs\Template\TemplateRendererInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureFactory implements ProcedureFactoryInterface
{
    /**
     * Parser.
     *
     * @var \JonnyW\PhantomJs\Parser\ParserInterface
     * @access protected
     */
    protected $parser;

    /**
     * Cache handler.
     *
     * @var \JonnyW\PhantomJs\Cache\CacheInterface
     * @access protected
     */
    protected $cacheHandler;

    /**
     * Template renderer.
     *
     * @var \JonnyW\PhantomJs\Template\TemplateRendererInterface
     * @access protected
     */
    protected $renderer;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     */
    public function __construct(ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $this->parser       = $parser;
        $this->cacheHandler = $cacheHandler;
        $this->renderer     = $renderer;
    }

    /**
     * Create new procedure instance.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    public function createProcedure()
    {
        $procedure = new Procedure(
            $this->parser,
            $this->cacheHandler,
            $this->renderer
        );

        return $procedure;
    }
}
