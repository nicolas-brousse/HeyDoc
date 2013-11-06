<?php

namespace HeyDoc;

class Parsor
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function parse(Page $page)
    {
        $content = $page->getContent();

        if ($page->getFormat() == Page::FORMAT_MARKDOWN) {
            $content = $this->container->get('markdown_parser')->transformMarkdown($content);
        }

        return $content;
    }
}
