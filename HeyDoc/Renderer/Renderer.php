<?php

namespace HeyDoc\Renderer;

use HeyDoc\Container;
use HeyDoc\Page;

class Renderer
{
    /** @var Container  $container  The container **/
    protected $container;

    /** @var null|Theme  $theme  Theme **/
    protected $theme;

    /** @var null|Page  $page  Page **/
    protected $page;

    /**
     * Construct the Renderer with container
     *
     * @param Container  $container  The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Render a Page an get the content
     *
     * @param Page  $page  Page to render
     *
     * @return string
     */
    public function render(Page $page)
    {
        $this->page = $page;

        $this->loadTheme();

        return $this->container->get('twig')->render(
            $this->getViewName(),
            array(
                'app'     => array(
                    'config'  => $this->container->get('config'),
                    'request' => $this->container->get('request'),
                ),
                'page'    => $page,
                'content' => $this->container->get('parser')->parse($this->page),
            )
        );
    }

    /**
     * Get the view name from Page layout
     *
     * @return string
     */
    private function getViewName()
    {
        return ($this->page->getLayout() ? mb_strtolower($this->page->getLayout()) : 'default') . '.twig';
    }

    /**
     * Load Theme
     */
    private function loadTheme()
    {
        $themes = $this->container->get('themes');
        $this->theme  = $themes->getTheme(
            $this->container->get('config')->get('theme')
        );

        $this->container->get('twig')->getLoader()
            ->setPaths(array_unique(array($this->theme->getPath())))
        ;
    }
}
