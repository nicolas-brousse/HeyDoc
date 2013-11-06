<?php

namespace HeyDoc\Renderer;

use Symfony\Component\Finder\SplFileInfo;

class Theme
{
    private $directory;

    /**
     *
     */
    public function __construct(SplFileInfo $directory)
    {
        $this->directory = $directory;
    }

    public function getPath()
    {
        return $this->directory->getRealPath();
    }
}
