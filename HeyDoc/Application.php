<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Application
{
    protected $container;

    protected $page;

    public function __construct($appBaseDir, Request $request)
    {
        $this->container = new Container($appBaseDir);
        $this->container['request'] = $request;
    }

    public function run()
    {
        try {
            $this->prepare();
            $this->render();
        }
        catch (NotFoundException $e) {
            $response = new Response($e->getMessage(), 404);
            $response->send();
        }
    }

    protected function prepare()
    {
        $this->page = $this->container->get('router')->process();
    }

    protected function render()
    {

        $response = new Response();
        $response->send();
    }
}
