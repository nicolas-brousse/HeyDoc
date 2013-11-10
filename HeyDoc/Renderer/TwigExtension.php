<?php

namespace HeyDoc\Renderer;

use HeyDoc\HeyDoc;
use HeyDoc\Container;

use Twig_Function_Method;
use Twig_Filter_Method;

class TwigExtension extends \Twig_Extension
{
    /** @var Container  $container  The container **/
    protected $container;

    /**
     *
     *
     * @param Container  $container  The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'markdown_transform' => new Twig_Filter_Method($this, 'markdownTransform', array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'path'     => new Twig_Function_Method($this, 'getPath', array()),

            'heydoc_homepage' => new Twig_Function_Method($this, 'getHeyDocHomepage', array()),
            'heydoc_version'  => new Twig_Function_Method($this, 'getHeyDocVersion', array()),
        );
    }

    public function getPath($path)
    {
        return $this->container->get('request')->getBaseUrl() . $path;
    }
    public function getHeyDocHomepage()
    {
        return 'https://github.com/nicolas-brousse/HeyDoc';
    }

    public function getHeyDocVersion()
    {
        return HeyDoc::VERSION;
    }

    public function markdownTransform($markdown)
    {
        return $this->container->get('markdown_parser')->transform($markdown);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'heydoc';
    }
}
