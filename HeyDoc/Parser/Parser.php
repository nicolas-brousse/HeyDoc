<?php

namespace HeyDoc\Parser;

use HeyDoc\Container;
use HeyDoc\Page;

class Parser
{
    /** @var Container  $container  The container **/
    protected $container;

    /**
     * Construct
     *
     * @param Container  $container  The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Parse a Page content an get it
     *
     * @param Page  $page  The Page to parse
     */
    public function parse(Page $page)
    {
        $content = $page->getContent();

        if ($page->getFormat() == Page::FORMAT_MARKDOWN) {
            $content = $this->container->get('markdown_parser')->transform($content);
        }

        // @todo  Highlight
        //        $this->container->get('highlighter')->highlight($code, $language);

        return $this->container->get('twig_string')->render(
            $content,
            array(
                'app'  => array(
                    'config'  => $this->container->get('config'),
                    'request' => $this->container->get('request'),
                ),
                'page' => $page,
            )
        );
    }
}
