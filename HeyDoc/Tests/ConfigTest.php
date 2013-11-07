<?php

namespace HeyDoc\Tests;

use HeyDoc\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = new Config(array(
            'title' => 'Lorem',
        ));

        $this->assertNotNull($config);
    }

    public function testHas()
    {
        $config = new Config(array(
            'title' => 'Lorem',
        ));

        $this->assertTrue($config->has('title'));
        $this->assertTrue($config->has('theme'));
        $this->assertFalse($config->has('unexistant.key'));
    }

    public function testGet()
    {
        $config = new Config(array(
            'title' => 'Lorem',
            'key'   => 'value',
        ));

        $this->assertEquals($config->get('title'), 'Lorem');
        $this->assertEquals($config->get('key'), 'value');
    }

    public function testGetDefaults()
    {
        $config = new Config(array(
            'title' => 'Lorem',
            'key'   => 'value',
        ));

        $this->assertEquals($config->getDefaults(), array(
            'theme'      => 'default',
            'debug'      => false,
            'title'      => 'HeyDoc',
            'theme_dirs' => array(),
        ));
    }

    public function testCall()
    {
        $config = new Config(array(
            'title' => 'Lorem',
            'key'   => 'value',
        ));

        $this->assertEquals($config->title(), 'Lorem');
        $this->assertEquals($config->key(), 'value');
    }
}
