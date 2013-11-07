<?php

namespace HeyDoc\Tests\Renderer;

use HeyDoc\Container;
use HeyDoc\Page;
use HeyDoc\Request;
use HeyDoc\Tree;
use HeyDoc\Renderer\Renderer;

use Symfony\Component\Finder\SplFileInfo;

class RendererTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = new Container();
        $this->container->setRequest(new Request());
        $this->container->setWebDir(__DIR__ . '/../Resources/web');
        $this->container->load();
    }

    public function testConstruct()
    {
        $renderer = new Renderer(new Container());

        $this->assertNotNull($renderer);
    }

    public function testRender()
    {
        $page    = $this->container->get('tree')->getPages()->offsetGet(0);
        $content = $this->container->get('renderer')->render($page);

        $this->assertContains('<h2>TEST MARKDOWN</h2>', $content);
        $this->assertContains('<h1>' . $page->getTitle() . '</h1>', $content);
    }
}
