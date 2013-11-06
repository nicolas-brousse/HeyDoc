<?php

namespace HeyDoc;

class Router
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function process()
    {
        $paths = explode('/', ltrim($this->getRequestPath(), '/'));

        return $this->findPage($this->container->get('tree'), $paths);
    }

    /**
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
                if ($path === $page->getName()) {
                    return $page;
                }
            }
        }

        // throw new NotFoundException(sprintf("Path %s does not exist", implode('/', $paths)));
    }

    protected function getRequestPath()
    {
        return str_replace($this->container->get('request')->getBaseUrl(), '', $this->container->get('request')->getRequestUri());
    }
}
