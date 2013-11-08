<?php

namespace HeyDoc\Renderer;

use HeyDoc\Container;
use HeyDoc\Page;

class Renderer
{
    /** @var Container  $container  The container **/
    protected $container;

    /** @var array  $config  Config of the Renderer **/
    protected $config;


    /**
     * Construct the Renderer with container
     *
     * @param Container  $container  The container
     */
    public function __construct(Container $container, array $config = array())
    {
        $this->container = $container;
        $this->config    = array_replace(array(
            'cache' => $this->container->get('root_dir') . '/cache/renderer',
            'debug' => false,
        ), $config);
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
        if ($this->isCacheEnabled() && $this->config['debug'] != false)
        {
            if ($cachedPage = $this->getCachedPage($page)) {
                return $cachedPage;
            }
        }

        $this->loadTheme();

        if ($this->isCacheEnabled()) {
            return $this->cachePage($page);
        }

        return $this->getRendererPage($page);
    }

    /**
     * Get cached page if exists
     *
     * @param Page  $page  Page to render
     */
    private function getCachedPage(Page $page)
    {
        $filepath = $this->generateCachedPagepath($page);

        if ($this->container->get('fs')->exists($filepath)) {
            // @todo  Use SplFileInfo SF
            return file_get_contents($filepath);
        }
    }

    /**
     * Generate the path of the cached page
     *
     * @param Page  $page  Page to render
     *
     * @return string  Path
     */
    private function generateCachedPagepath(Page $page)
    {
        $filename = md5($page->getUrl() . $page->getUpdatedAt()->format('U'));

        return implode(DIRECTORY_SEPARATOR, array(
            $this->config['cache'],
            substr($filename, 0, 2),
            substr($filename, 2, 2),
            $filename,
        ));
    }

    /**
     * Generate file cache for page and save it
     *
     * @param Page  $page  Page to render
     *
     * @return string  Html content
     */
    private function cachePage(Page $page)
    {
        $content = $this->getRendererPage($page);

        $filepath = $this->generateCachedPagepath($page);
        $this->container->get('fs')->mkdir(dirname($filepath));

        // Save
        $cacheFile = new \SplFileInfo($filepath);
        $fo = $cacheFile->openFile('w');
        $fo->fwrite($content);

        return $content;
    }

    /**
     * Render a Page
     *
     * @param Page  $page  Page
     *
     * @return string  Html content
     */
    private function getRendererPage(Page $page)
    {
        return $this->container->get('twig')->render(
            $this->getViewNameForPage($page),
            array(
                'app'     => array(
                    'config'  => $this->container->get('config'),
                    'request' => $this->container->get('request'),
                ),
                'page'    => $page,
                'content' => $this->container->get('parser')->parse($page),
            )
        );
    }

    /**
     * Get the view name from Page layout
     *
     * @param Page  $page  Page
     *
     * @return string
     */
    private function getViewNameForPage(Page $page)
    {
        return ($page->getLayout() ? mb_strtolower($page->getLayout()) : 'default') . '.twig';
    }

    /**
     * Load Theme into Twig
     */
    private function loadTheme()
    {
        $themes = $this->container->get('themes');
        $theme  = $themes->getTheme(
            $this->container->get('config')->get('theme')
        );

        $this->container->get('twig')->getLoader()
            ->setPaths(array_unique(array($theme->getPath())))
        ;
    }

    /**
     * Is cache enabled
     *
     * @return boolean
     */
    private function isCacheEnabled()
    {
        return $this->config['cache'] && is_dir(dirname($this->config['cache']));
    }
}
