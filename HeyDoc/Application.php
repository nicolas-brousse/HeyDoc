<?php

namespace HeyDoc;

use dflydev\markdown\MarkdownExtraParser;

use HeyDoc\Exception\NotFoundException;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Application
{
    /** @var  **/
    protected $container;

    /** @var  **/
    protected $page;

    /**
     *
     */
    public function __construct(Request $request)
    {
        $this->container = new Container();
        $this->container->setRequest($request);
        $this->container->load();

        if ($this->container->get('config')->get('debug')) {
            ErrorHandler::register(true);
        }
        else {
            ErrorHandler::quiet();
        }
    }

    /**
     *
     */
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

    /**
     *
     */
    protected function prepare()
    {
        $this->page = $this->container->get('router')->process();
    }

    /**
     *
     */
    protected function process()
    {
        $response = new Response();
        $response->setContent(
            $this->container->get('renderer')->render($this->page)
        );
        $response->send();
    }
}
