<?php

namespace HeyDoc;

class Renderer
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
     * Render a Page an get the content
     *
     * @param Page  $page  Page to render
     *
     * @return string
     */
    public function render(Page $page)
    {
        $content = '';

        // TODO: Parse page vars like {{ site.baseUrl }}? Use twig?
        $content = $this->container->get('parser')->parse($page);
        // TODO: Process twig

        return $content;
    }
}
