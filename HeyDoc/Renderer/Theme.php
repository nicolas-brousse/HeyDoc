<?php

namespace HeyDoc\Renderer;

class Theme
{
    private $directory;
    private $name;

    /**
     * Contruct
     *
     * @param SplFileInfo  $directory  Root directory of Theme
     */
    public function __construct(\SplFileInfo $directory)
    {
        if (! $directory->isDir()) {
            throw new \InvalidArgumentException(sprintf(
                '%s can not be created because "%s" is not a directory or does not exists',
                __CLASS__,
                $directory->getPathname()
            ));
        }
        $this->directory = $directory;
        $this->name      = mb_strtolower($this->directory->getBasename());
    }

    public function getName()
    {
        return $this->name;
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
