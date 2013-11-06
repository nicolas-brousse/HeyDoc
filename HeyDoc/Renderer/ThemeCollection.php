<?php

namespace HeyDoc\Renderer;

use HeyDoc\Container;

use Symfony\Component\Finder\Finder;

class ThemeCollection
{
    private $themePaths;
    private $themes;

    /**
     *
     *
     * @param array  $themePaths  The container
     */
    public function __construct(array $themePaths)
    {
        $this->themePaths = $themePaths;
        $this->themes     = new \ArrayObject();

        $this->load();
    }

    public function addTheme($name, Theme $theme)
    {
        $this->themes->offsetSet($name, $theme);
    }

    public function getTheme($themeName)
    {
        return $this->themes->offsetGet($themeName);
    }

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
