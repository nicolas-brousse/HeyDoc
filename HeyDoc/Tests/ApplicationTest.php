<?php

namespace HeyDoc\Tests;

use HeyDoc\Application;
use HeyDoc\Request;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    // private $container;

    // public function setUp()
    // {
    //     $this->container = new Container();
    //     $this->container->setRequest(new Request());
    //     $this->container->setWebDir(__DIR__ . '/../Resources/web');
    //     $this->container->load();
    // }

    public function testConstruct()
    {
        $request = Request::create('/', 'GET', array(), array(), array(), array(
            'SCRIPT_FILENAME' => __DIR__ . '/Resources/web/index.php',
        ));

        $application = new Application($request);

        $this->assertNotNull($application);
    }

    public function testRun()
    {
        $request = Request::create('/', 'GET', array(), array(), array(), array(
            'SCRIPT_FILENAME' => __DIR__ . '/Resources/web/index.php',
        ));

        $application = new Application($request);

        ob_start();
        $application->run();
        $response = ob_get_clean();

        $this->assertNotEmpty($response);
    }
}
