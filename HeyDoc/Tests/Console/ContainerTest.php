<?php

namespace HeyDoc\Tests\Console;

use HeyDoc\Console\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $container = new Container();

        $this->assertNotNull($container);
    }
}
