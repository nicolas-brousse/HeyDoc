<?php

namespace HeyDoc\Renderer;

use Symfony\Component\Finder\SplFileInfo;

class Theme
{
    private $directory;

    /**
     * Contruct
     *
     * @param SplFileInfo  $directory  Root directory of Theme
     */
    public function __construct(SplFileInfo $directory)
    {
        if (! $directory->isDir()) {
            throw new \InvalidArgumentException(sprintf(
                '%s can not be created because "%s" is not a directory or does not exists',
                __CLASS__,
                $directory->getPathname()
            ));
        }
        $this->directory = $directory;
    }

    /**
     * Get root path of the theme
     *
     * @return string
     */
    public function getPath()
    {
        return $this->directory->getRealPath();
    }
}
