<?php

namespace HeyDoc;

class Parser
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
     * Parse a Page content an get it
     *
     * @param Page  $page  The Page to parse
     */
    public function parse(Page $page)
    {
        $content = $page->getContent();

        if ($page->getFormat() == Page::FORMAT_MARKDOWN) {
            $content = $this->container->get('markdown_parser')->transformMarkdown($content);
        }

        $twig   = new \Twig_Environment(new \Twig_Loader_String());

        return $twig->render(
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
