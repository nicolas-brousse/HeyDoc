<?php

namespace HeyDoc\Tests\Console;

use HeyDoc\Console\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $application = new Application();

        $this->assertNotNull($application->get('setup'));
        $this->assertNotNull($application->get('export'));
        $this->assertNotNull($application->get('check'));
    }
}
