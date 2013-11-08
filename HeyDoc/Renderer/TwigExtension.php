<?php

namespace HeyDoc\Renderer;

use HeyDoc\HeyDoc;
use HeyDoc\Container;

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
        return array();
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'heydoc_version'     => new \Twig_Function_Method($this, 'getHeyDocVersion', array()),
            'markdown_transform' => new \Twig_Function_Method($this, 'markdownTransform', array('is_safe' => array('html'))),
        );
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
