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
     * Contruct
     *
     * @param Request  $request  Request to perform (Move it into run() method?)
     */
    public function __construct(Request $request)
    {
        $this->container = new Container();
        $this->container->setRequest($request);
        $this->container->load();

        if ($this->container->get('config')->get('debug') == true) {
            ErrorHandler::register(true);
        }
        else {
            ErrorHandler::quiet();
        }
    }

    /**
     * Run the application
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
     * Prepare
     */
    protected function prepare()
    {
        $this->page = $this->container->get('router')->process();
    }

    /**
     * Process
     */
    protected function process()
    {
        $response = new Response();
        $response->prepare($this->container->get('request'));
        $response->setContent(
            $this->container->get('renderer')->render($this->page)
        );
        $response->headers->add(array('Last-Modified' => $this->page->getUpdatedAt()->format(\DateTime::RFC2822)));
        $response->send();
    }
}
