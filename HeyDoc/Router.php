<?php

namespace HeyDoc;

use HeyDoc\Exception\NotFoundException;

class Router
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
     * Use the request to found the called page and return it
     *
     * @return Page  $page  The page found
     */
    public function process()
    {
        $paths = explode('/', trim($this->container->get('request')->getPath(), '/'));
        return $this->findPage($this->container->get('tree'), $paths);
    }

    /**
     *
     *
     * @param Tree   $tree   Tree where seek
     * @param array  $paths  Exploded path
     *
     * @return Page  The found page
     *
     * @throws NotFoundException
     */
    private function findPage(Tree $tree, array $paths)
    {
        $path = current($paths);

        if (count($paths) > 1)
        {
            foreach ($tree->getChildren() as $child)
            {
                if ($path === $child->getName()) {
                    return $this->findPage($child, array_splice($paths, 1));
                }
            }
        }
        else
        {
            foreach ($tree->getPages() as $page)
            {
                if ($path === '' && $page->getName() === 'index') {
                    return $page;
                }
                if ($path === $page->getName()) {
                    return $page;
                }
            }
        }

        throw new NotFoundException(sprintf('Path "/%s" does not exist', implode('/', $paths)));
    }
}
