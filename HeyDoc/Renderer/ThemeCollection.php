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
     * Add a Theme into the collection
     *
     * @param string  $name   The name of the Theme
     * @param Theme   $theme  The Theme
     */
    public function addTheme($name, Theme $theme)
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
            $this->addTheme(mb_strtolower($dir->getRelativePathname()), new Theme($dir));
        }
    }
}
