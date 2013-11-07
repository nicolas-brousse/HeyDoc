<?php

namespace HeyDoc\Tests\Renderer;

use HeyDoc\Container;
use HeyDoc\Request;
use HeyDoc\Renderer\Renderer;

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
        // @todo  Do a better test on injected vars

        foreach ($this->container->get('tree')->getPages() as $page)
        {
            $content = $this->container->get('renderer')->render($page);

            // $this->assertContains('<h2>TEST MARKDOWN</h2>', $content);
            $this->assertContains('<h1>' . $page->getTitle() . '</h1>', $content);
        }
    }
}
