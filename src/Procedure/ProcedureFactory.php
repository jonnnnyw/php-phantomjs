<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Procedure;

use JonnyW\PhantomJs\Engine;
use JonnyW\PhantomJs\Cache\CacheInterface;
use JonnyW\PhantomJs\Parser\ParserInterface;
use JonnyW\PhantomJs\Template\TemplateRendererInterface;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureFactory implements ProcedureFactoryInterface
{
    /**
     * PhantomJS engine.
     *
     * @var \JonnyW\PhantomJs\Engine
     */
    protected $engine;

    /**
     * Parser.
     *
     * @var \JonnyW\PhantomJs\Parser\ParserInterface
     */
    protected $parser;

    /**
     * Cache handler.
     *
     * @var \JonnyW\PhantomJs\Cache\CacheInterface
     */
    protected $cacheHandler;

    /**
     * Template renderer.
     *
     * @var \JonnyW\PhantomJs\Template\TemplateRendererInterface
     */
    protected $renderer;

    /**
     * Internal constructor.
     *
     * @param \JonnyW\PhantomJs\Engine                             $engine
     * @param \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     */
    public function __construct(Engine $engine, ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $this->engine = $engine;
        $this->parser = $parser;
        $this->cacheHandler = $cacheHandler;
        $this->renderer = $renderer;
    }

    /**
     * Create new procedure instance.
     *
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    public function createProcedure()
    {
        $procedure = new Procedure(
            $this->engine,
            $this->parser,
            $this->cacheHandler,
            $this->renderer
        );

        return $procedure;
    }
}
