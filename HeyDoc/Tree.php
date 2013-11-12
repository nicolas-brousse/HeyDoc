<?php

namespace HeyDoc;

use Symfony\Component\Finder\Finder;

class Tree
{
    protected $pages;
    protected $parent;
    protected $children;
    protected $directory;

    /**
     * Construct.
     *
     * @param string  $directory  Root directory of this Tree
     * @param Tree    $parent     Tree parent
     */
    public function __construct($directory, Tree $parent = null)
    {
        $this->directory = $directory;
        $this->parent    = $parent;

        $this->pages     = new \ArrayObject();
        $this->children  = new \ArrayObject();

        $this->load();
    }

    public function getName()
    {
        return $this->parent
            ? preg_replace("/^\d+_/", '', basename($this->directory))
            : null
        ;
    }

    public function getUrl()
    {
        $parent = $this->getParent() ? $this->getParent()->getUrl() . '/' : '';
        return $parent . $this->getName();
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(Tree $tree)
    {
        $this->children->append($tree);
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function addPage(Page $page)
    {
        $this->pages->offsetSet($page->getName(), $page);
    }

    public function getPage($pageName)
    {
        if (! $this->pages->offsetExists($pageName)) {
            throw new \Exception(sprintf('Page named "%s" does not exists in this %s', $pageName, __CLASS__));
        }
        return $this->pages->offsetGet($pageName);
    }

    public function refresh()
    {
        $this->pages     = new \ArrayObject();
        $this->children  = new \ArrayObject();

        $this->load();
    }

    protected function load()
    {
        $finder = new Finder();
        $dirs   = $finder->directories()
            ->in($this->directory)
            ->depth('0')
        ;
        foreach ($dirs as $dir) {
            $this->addChild(new Tree($dir->getPathname(), $this));
        }

        $finder = new Finder();
        $files  = $finder->files()
            ->in($this->directory)
            ->depth('0')
            ->name('*.md')->name('*.markdown')
            ->name('*.htm')->name('*.html')
        ;
        foreach ($files as $file) {
            $this->addPage(new Page($file, $this));
        }
    }
}
