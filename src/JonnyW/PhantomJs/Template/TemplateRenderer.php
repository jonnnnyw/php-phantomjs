<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Template;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class TemplateRenderer implements TemplateRendererInterface
{
    /**
     * Twig environment instance.
     *
     * @var \Twig_Environment
     * @access protected
     */
    protected $twig;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render template.
     *
     * @access public
     * @param  string $template
     * @param  array  $context  (default: array())
     * @return string
     */
    public function render($template, array $context = array())
    {
        $template = $this->twig->createTemplate($template);

        return $template->render($context);
    }
}
