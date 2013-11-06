<?php

namespace HeyDoc;

class Renderer
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function render(Page $page)
    {
        $content = '';

        // TODO: Parse page vars like {{ site.baseUrl }}? Use twig?
        $content = $this->container->get('parser')->parse($page);
        // TODO: Process twig

        return $content;
    }
}
