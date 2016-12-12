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
use JonnyW\PhantomJs\IO\InputInterface;
use JonnyW\PhantomJs\IO\OutputInterface;
use JonnyW\PhantomJs\Exception\NotWritableException;
use JonnyW\PhantomJs\Exception\ProcedureFailedException;
use JonnyW\PhantomJs\StringUtils;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Procedure implements ProcedureInterface
{
    /**
     * PhantomJS engine.
     *
     * @var \JonnyW\PhantomJs\Engine
     */
    protected $engine;

    /**
     * Parser instance.
     *
     * @var \JonnyW\PhantomJs\Parser\ParserInterface
     */
    protected $parser;

    /**
     * Cache handler instance.
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
     * Procedure template.
     *
     * @var string
     */
    protected $template;

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
     * Run procedure.
     *
     * @param \JonnyW\PhantomJs\IO\InputInterface  $input
     * @param \JonnyW\PhantomJs\IO\OutputInterface $output
     *
     * @throws \JonnyW\PhantomJs\Exception\ProcedureFailedException
     * @throws \JonnyW\PhantomJs\Exception\NotWritableException
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        try {
            $executable = $this->write(
                $this->compile($input, $output)
            );

            $descriptorspec = array(
                array('pipe', 'r'),
                array('pipe', 'w'),
                array('pipe', 'w'),
            );

            $process = proc_open(escapeshellcmd(sprintf('%s %s', $this->engine->getCommand(), $executable)), $descriptorspec, $pipes, null, null);

            if (!is_resource($process)) {
                throw new ProcedureFailedException('proc_open() did not return a resource');
            }

            $result = stream_get_contents($pipes[1]);
            $log = stream_get_contents($pipes[2]);

            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            $output->import(
                $this->parser->parse($result)
            );

            $this->engine->log($log);

            $this->remove($executable);
        } catch (NotWritableException $e) {
            throw $e;
        } catch (\Exception $e) {
            if (isset($executable)) {
                $this->remove($executable);
            }

            throw new ProcedureFailedException(sprintf('Error when executing PhantomJs procedure - %s', $e->getMessage()));
        }
    }

    /**
     * Set procedure template.
     *
     * @param string $template
     *
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get procedure template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Compile procedure.
     *
     * @param \JonnyW\PhantomJs\IO\InputInterface  $input
     * @param \JonnyW\PhantomJs\IO\OutputInterface $output
     */
    public function compile(InputInterface $input, OutputInterface $output)
    {
        return $this->renderer->render($this->getTemplate(), array('input' => $input, 'output' => $output));
    }

    /**
     * Write compiled procedure to cache.
     *
     * @param string $compiled
     *
     * @return string
     */
    protected function write($compiled)
    {
        return $this->cacheHandler->save(StringUtils::random(20), $compiled);
    }

    /**
     * Remove procedure script cache.
     *
     * @param string $filePath
     */
    protected function remove($filePath)
    {
        $this->cacheHandler->delete($filePath);
    }
}
