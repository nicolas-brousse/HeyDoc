<?php

namespace HeyDoc\Renderer;

use HeyDoc\Container;
use HeyDoc\Page;

class Renderer
{
    /** @var Container  $container  The container **/
    protected $container;

    protected $theme;

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
     * Render a Page an get the content
     *
     * @param Page  $page  Page to render
     *
     * @return string
     */
    public function render(Page $page)
    {
        $this->loadTheme();

        // TODO: Parse page vars like {{ site.baseUrl }}? Use twig?
        $content = $this->container->get('parser')->parse($page);

        $viewName = $page->getLayout() ? $page->getLayout() : 'default';

        return $this->container->get('twig')->render($viewName . '.twig', array(
            'site'    => $this->container->get('configs'),
            'page'    => $page,
            'content' => $content,
        ));
    }

    private function loadTheme()
    {
        $themes = $this->container->get('themes');
        $this->theme  = $themes->getTheme(
            $this->container->get('configs')->get('theme')
        );

        $this->container->get('twig')->getLoader()
            ->setPaths(array_unique(array($this->theme->getPath())))
        ;
    }
}
