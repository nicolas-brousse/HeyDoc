<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use HeyDoc\Exception\NotFoundException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Application
{
    protected $container;

    protected $page;

    public function __construct(Request $request)
    {
        $this->container = new Container();
        $this->container['request'] = $request;
        $this->container->load();
    }

    public function run()
    {
        try {
            $this->prepare();
            $this->process();
        }
        catch (NotFoundException $e) {
            Response::createAndSend($e->getMessage(), 404);
        }
    }

    protected function prepare()
    {
        $this->page = $this->container->get('router')->process();
    }

    protected function process()
    {
        $response = new Response();
        $response->setBody(
            $this->container->get('renderer')->render($this->page)
        );
        $response->send();
    }
}
