<?php

namespace HeyDoc\Tests;

use HeyDoc\HeyDoc;

class HeyDocTest extends \PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $this->assertEquals(HeyDoc::VERSION, '0.0.1-beta');
    }
}
