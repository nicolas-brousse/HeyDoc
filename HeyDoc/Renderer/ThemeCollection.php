<?php

namespace HeyDoc\Renderer;

use HeyDoc\Container;

use Symfony\Component\Finder\Finder;

class ThemeCollection
{
    private $themePaths;
    private $themes;

    /**
     * Construct the ThemeCollection from theme paths
     *
     * @param array  $themePaths  The container
     */
    public function __construct(array $themePaths)
    {
        $this->themePaths = $themePaths;
        $this->themes     = new \ArrayObject();

        $this->load();
    }

    /**
     * Register a Theme into the ThemeCollection
     *
     * @param string  $name   The name of the Theme
     * @param Theme   $theme  The Theme
     */
    public function registerTheme($name, Theme $theme)
    {
        $this->themes->offsetSet($name, $theme);
    }

    /**
     * Get a Theme from his name
     *
     * @param string  $themeName  The name of the Name
     *
     * @return null|Theme
     */
    public function getTheme($themeName)
    {
        if (! $this->themes->offsetExists($themeName)) {
            throw new \Exception(sprintf('%s with name "%s" does not registered', __CLASS__, $themeName));
        }
        return $this->themes->offsetGet($themeName);
    }

    /**
     * Load Themes
     */
    private function load()
    {
        $finder = new Finder();
        $finder
            ->directories()
            ->depth('0')
        ;

        foreach ($this->themePaths as $path) {
            $finder->in($path);
        }

        foreach ($finder as $dir) {
            $t = new Theme($dir);
            $this->registerTheme($t->getName(), $t);
        }
    }
}
