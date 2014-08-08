<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Procedure;

use JonnyW\PhantomJs\ClientInterface;
use JonnyW\PhantomJs\Cache\CacheInterface;
use JonnyW\PhantomJs\Parser\ParserInterface;
use JonnyW\PhantomJs\Message\RequestInterface;
use JonnyW\PhantomJs\Message\ResponseInterface;
use JonnyW\PhantomJs\Template\TemplateRendererInterface;
use JonnyW\PhantomJs\Exception\NotWritableException;
use JonnyW\PhantomJs\Exception\ProcedureFailedException;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Procedure implements ProcedureInterface
{
    /**
     * Parser instance.
     *
     * @var \JonnyW\PhantomJs\Parser\ParserInterface
     * @access protected
     */
    protected $parser;

    /**
     * Cache handler instance.
     *
     * @var \JonnyW\PhantomJs\Cache\CacheInterface
     * @access protected
     */
    protected $cacheHandler;

    /**
     * Procedure template.
     *
     * @var string
     * @access protected
     */
    protected $procedure;

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
     * Run procedure.
     *
     * @access public
     * @param  \JonnyW\PhantomJs\ClientInterface                    $client
     * @param  \JonnyW\PhantomJs\Message\RequestInterface           $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface          $response
     * @throws \JonnyW\PhantomJs\Exception\ProcedureFailedException
     * @throws \Exception
     * @throws \JonnyW\PhantomJs\Exception\NotWritableException
     * @return void
     */
    public function run(ClientInterface $client, RequestInterface $request, ResponseInterface $response)
    {
        try {

            $template  = $this->getProcedure();
            $procedure = $this->renderer->render($template, array('request' => $request));

            $executable = $this->write($procedure);

            $descriptorspec = array(
                array('pipe', 'r'),
                array('pipe', 'w'),
                array('pipe', 'w')
            );

            $process = proc_open(escapeshellcmd(sprintf('%s %s', $client->getCommand(), $executable)), $descriptorspec, $pipes, null, null);

            if (!is_resource($process)) {
                throw new ProcedureFailedException('proc_open() did not return a resource');
            }

            $result = stream_get_contents($pipes[1]);
            $log    = stream_get_contents($pipes[2]);

            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            $response->import(
                $this->parser->parse($result)
            );

            $client->setLog($log);

            $this->remove($executable);

        } catch (NotWritableException $e) {
            throw $e;
        } catch (\Exception $e) {

            if (isset($executable)) {
                $this->remove($executable);
            }

            throw new ProcedureFailedException(sprintf('Error when executing PhantomJs procedure "%s" - %s', $request->getType(), $e->getMessage()));
        }
    }

    /**
     * Load procedure.
     *
     * @access public
     * @param  string                                $procedure
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    public function load($procedure)
    {
        $this->procedure = $procedure;

        return $this;
    }

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Write procedure script cache.
     *
     * @access protected
     * @param  string $procedure
     * @return string
     */
    protected function write($procedure)
    {
        $executable = $this->cacheHandler->save(uniqid(), $procedure);

        return $executable;
    }

    /**
     * Remove procedure script cache.
     *
     * @access protected
     * @param  string $filePath
     * @return void
     */
    protected function remove($filePath)
    {
        $this->cacheHandler->delete($filePath);
    }
}
